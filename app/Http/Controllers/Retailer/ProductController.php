<?php

namespace App\Http\Controllers\Retailer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\{Category, Product, ProductImage, ProductLocation, ProductUnavailability, Brand, City, RetailerBankInformation, Size, User, NeighborhoodCity, ProductDisableDate, State};
use Carbon\Carbon;
use DateTime, Stripe;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // DD('SADASDASD');
        $products = Product::with(['category', 'retailer.vendorBankDetails'])->where('user_id', auth()->user()->id)
            ->when((!is_null($request->status) && $request->status != 'all'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when(!is_null($request->search), function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })
            ->orderBy('id', 'DESC')
            ->paginate($request->global_pagination);

        return view('customer.edit_product', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('status', 'Active')->get();

        return view('retailer.product_add', compact('categories'));
    }

    public function store_img(Request $request)
    {
        try {
            if (isset($request->dataId)) {
                $this->RemoveImages($request->dataId);
                return response()->json([
                    'status' => true,
                ], 200);
            }
            $productMeta = [];
            if (count($request->file('files')) > 0) {
                foreach ($request->file('files') as $file) {
                    $image = s3_store_image($file, 'products/images');
                    if ($image != null) {
                        $productMeta[] = [
                            'image_token' => $_COOKIE['img_token'],
                            'file' => $image['name'],
                            'url' => $image['url'],
                            'type' => 'gallery'
                        ];
                    }
                }
            }
            if (count($productMeta)) {
                ProductImage::insert($productMeta);
            }
            $images = ProductImage::where(function ($q) use ($request) {
                $q->whereImageToken($_COOKIE['img_token'])->orwhere('product_id', $request->productId);
            })->get();
            return response()->json([
                'status' => true,
                'html' => view('components.preview-image', compact('images'))->render(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success'    =>  false,
                'msg'      =>  $e->getMessage()
            ]);
        }
    }

    public function RemoveImages($dataId)
    {
        try {
            $getimage = ProductImage::whereId($dataId)->first();
            if ($getimage) {
                Storage::disk('s3')->delete('products/images/' . $getimage->file);
                $getimage->delete();
            }
            return response()->json([
                'status' => true,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success'    =>  false,
                'msg'      =>  $e->getMessage()
            ]);
        }
    }

    public function deleteimages(Request $request, $token)
    {

        try {
            $getimage = ProductImage::where('image_token', $token)->get();

            foreach ($getimage as $image) {

                if ($image) {
                    Storage::disk('s3')->delete('products/images/' . $image->file);
                    $image->delete();
                }
            }
            return response()->json([
                'status' => true,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success'    =>  false,
                'msg'      =>  $e->getMessage()
            ]);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function store(StoreProductRequest $request)
    {
        // dd('herererer',$request->all());

        try {
            DB::beginTransaction();
            $category = jsdecode_userdata($request->category);
            if($request->input('product_complete_location')!=null)
            {
                // Geocoding the complete location
                $product_complete_location = $request->input('product_complete_location');
                $address = urlencode($product_complete_location);
                $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=" . config('services.google_maps.api_key');
                $response = file_get_contents($url);
                $raw_address = json_decode($response, true);
                if (!empty($raw_address['results'])) {
                    $formatted_address = json_encode($raw_address['results'][0]);
                }
            }


            // Create the product
            $userId = auth()->user()->id;
            $data = [
                'name' => $request->product_name,
                'description' => $request->description,
                'user_id' => $userId,
                'category_id' => $category,
                'subcat_id' => $request->subcategory,
                'product_condition' => $request->product_condition,
                'rent_price' => $request->rent_price ?? 0,
                'rent_day' => $request->rent_price_day,
                'rent_week' => $request->rent_price_week,
                'rent_month' => $request->rent_price_month,
                'min_days_rent_item' => $request->min_rent_days,
                'product_market_value' => $request->product_market_value,
                'product_link' => $request->product_link,
                'size' => $request->size ?? null,
                // 'brand' => $request->brand ?? null,
                'color' => $request->color ?? null,
                'price' => $request->price ?? null,
                'city' => $request->city ?? null,
                'state' => $request->state ?? null,
                'country' => $request->country ?? null,
                'status' => '1',
                'modified_by' => $userId,
                'modified_user_type' => 'Self',
                'non_available_dates' => $request->non_available_dates ?? 0,
                'cancellation_policy' => $request->cancellation_policy ,
            ];
            if(isset($request->other_brand)){
                Brand::updateOrCreate(['name'=> $request->other_brand],
                    ['name'=> $request->other_brand,
                ]);
            }
            $data['brand'] = $request->brand == "Other" ? (isset($request->other_brand) ? $request->other_brand : 'Other') : $request->brand;

            $product = Product::create($data);

            // Handling images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $fileName = $product->id . '_' . time() . '_' . $index . '.' . $image->getClientOriginalExtension();
                    $filePath = $image->storeAs('products/images', $fileName, 'public');

                    ProductImage::create([
                        'product_id' => $product->id,
                        'file_name' => $fileName,
                        'file_path' => $filePath,
                    ]);
                }
            }
            // dd('images');
            // Store product location
            ProductLocation::create([
                'product_id' => $product->id,
                'address1' => $request->address1 ?? null,
                'address2' => $request->address2 ?? null,
                'manul_pickup_location' => $request->manual_location ? '1' : '0',
                'shipment' => $request->shipment ? '1' : '0',
                'country' => $request->country ?? null,
                'state' => $request->state ?? null,
                'city' => $request->city ?? null,
                'pick_up_location' => $request->product_complete_location ?? null,
                'raw_address' => $formatted_address ?? null,
                'postcode' => $request->zipcode ?? null,
            ]);

            // Handle disable dates
            if ($request->has('non_availabile_dates') && $request->filled('non_availabile_dates')) {

                foreach ($request->non_availabile_dates as $dateRange) {
                    // dd($request->non_availabile_dates,'location');

                    // Check if the date range has a valid format with " - "
                    if (strpos($dateRange, ' - ') !== false) {
                        list($startDateStr, $endDateStr) = array_map('trim', explode(' - ', $dateRange));
                    } else {
                        // If there's no range separator, assume it's a single date
                        $startDateStr = $endDateStr = trim($dateRange);
                    }

                    $startDate = Carbon::parse($startDateStr);
                    $endDate = Carbon::parse($endDateStr);

                    // Insert the selected date range
                    for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                        ProductDisableDate::create([
                            'product_id' => $product->id,
                            'disable_date' => $date->format('Y-m-d'),
                            'added_by' => auth()->id(),
                        ]);
                    }
                }
            }


            // Automatically add 4 days from the creation date
            $createdDate = Carbon::now()->startOfDay();
            for ($i = 1; $i <= 4; $i++) {
                $futureDate = $createdDate->copy()->addDays($i);
                ProductDisableDate::create([
                    'product_id' => $product->id,
                    'disable_date' => $futureDate->format('Y-m-d'),
                    'added_by' => auth()->id(),

                ]);
            }
            // dd('sample');
            DB::commit();
            return redirect()->route('product')->with('success', "Your product has been uploaded successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {

        // dd("HERE EDITTT");
        $product = $this->getProduct($id);

        $sub_categories = Category::where('status', '1')->where('parent_id', $product->category_id)->get();
        $subcatId = ($product->subcat_id) ? $product->subcat_id : '';

        $category = Category::with(['size_type'])->where('status', '1')->where('id', $product->category_id)->first();
        $types = isset($category) && count($category->size_type) > 0 ? $category->size_type : [];
        $selectedType = ($product->get_size) ? $product->get_size->type : '';

        $sizes = Size::where('status', '1')->where('type', $selectedType)->get();
        // dd("size",$sizes);
        $selectedSize = isset($product) ? $product->size : '';

        $city = City::where('id', $product->city)->first();
        $state = State::where('id', $product->state)->first();
        $pickuplocation = ProductLocation::where('product_id', $product->id)->first();

        // Retrieve and process the disabled dates
        $disableDates = $product->disableDates->where('added_by', auth()->id());
        $disableDatesArray = [];

        // Extract the 'disable_date' field and remove duplicates
        foreach ($disableDates as $disableddate) {
            $disableDatesArray[] = $disableddate->disable_date;
        }
        $disableDatesArray = array_unique($disableDatesArray);

        // Sort the dates in ascending order
        usort($disableDatesArray, function ($a, $b) {
            return strtotime($a) - strtotime($b);
        });

        // Group continuous dates into ranges
        $ranges = [];
        $start = null;
        $end = null;

        foreach ($disableDatesArray as $key => $date) {
            $currentDate = Carbon::parse($date);

            if (!$start) {
                // Start a new range
                $start = $currentDate;
                $end = $currentDate;
            } else {
                // Check if the current date is continuous (1 day after the previous date)
                $previousDate = Carbon::parse($disableDatesArray[$key - 1]);
                if ($currentDate->diffInDays($previousDate) == 1) {
                    // Extend the current range
                    $end = $currentDate;
                } else {
                    // Add the previous range to the result
                    $ranges[] = ($start->eq($end)) ? $start->format('Y-m-d') : $start->format('m/d/Y') . ' - ' . $end->format('m/d/Y');

                    // Start a new range
                    $start = $currentDate;
                    $end = $currentDate;
                }
            }
        }

        // Add the last range to the result
        if ($start && $end) {
            $ranges[] = ($start->eq($end)) ? $start->format('Y-m-d') : $start->format('m/d/Y') . ' - ' . $end->format('m/d/Y');
        }

        // Display the ranges
        // dd($ranges);
        // end
        // dd("here",$disabledDates);
        // if ($disabledDates->isNotEmpty()) {
        //     $sortedDates = $disabledDates->sortBy('disable_date');
        //     $firstDate = \Carbon\Carbon::parse($sortedDates->first()->disable_date)->format('Y-m-d');
        //     $lastDate = \Carbon\Carbon::parse($sortedDates->last()->disable_date)->format('Y-m-d');

        //     $formattedDates = $firstDate . ' - ' . $lastDate;
        // } else {
        //     $formattedDates = '';
        // }

        return view('customer.update_product', compact('product', 'sub_categories', 'category', 'sizes', 'city', 'state', 'pickuplocation', 'ranges'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function update(UpdateProductRequest $request, $id)
    {
        if($request->input('product_complete_location')!=null)
        {
        $product_complete_location = $request->input('product_complete_location');
        $address = urlencode($product_complete_location);
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=" . config('services.google_maps.api_key');
        $response = file_get_contents($url);
        $raw_address = json_decode($response, true);
        }
        if (!empty($raw_address['results'])) {
            $formatted_address = json_encode($raw_address['results'][0]);
        }
        try {
            $product = Product::findOrFail(jsdecode_userdata($id));
            $userId = auth()->user()->id;

            $existingImages = $product->images;
            foreach ($existingImages as $image) {
                Storage::disk('public')->delete($image->file_path);
                $image->delete();
            }

            $data = [
                'name' => $request->product_name,
                'description' => $request->description,
                'category_id' => jsdecode_userdata($request->category),
                'subcat_id' => $request->subcategory,
                'product_condition' => $request->product_condition,
                'rent_price' => $request->rent_price ?? 0,
                'rent_day' => $request->rent_price_day,
                'rent_week' => $request->rent_price_week,
                'rent_month' => $request->rent_price_month,
                'min_days_rent_item' => $request->min_rent_days,
                'product_market_value' => $request->product_market_value,
                'product_link' => $request->product_link,
                'size' => $request->size ?? null,
                'brand' => $request->brand ?? null,
                'color' => $request->color ?? null,
                'price' => $request->price ?? null,
                'city' => $request->city ?? null,
                'state' => $request->state,
                'country' => $request->country,
                'modified_by' => $userId,
                'modified_user_type' => 'Self',
                'non_available_dates' => $request->non_available_dates ?? 1,
                'cancellation_policy' => $request->cancellation_policy ,

            ];
            if(isset($request->other_brand)){
                Brand::updateOrCreate(['name'=> $request->other_brand],
                    ['name'=> $request->other_brand,
                ]);
            }else{
                $check = Brand::updateOrCreate(['name'=> 'Other'],
                    ['name'=> 'Other',
                ]);
            }
            if(isset($request->brand)){
                $data['brand'] = $request->brand == "Other" ? (isset($request->other_brand) ? $request->other_brand:'Other') : $request->brand;
            }else{
                $data['brand'] = 'Other';
            }

            $product->update($data);

            $currentImageIds = $product->allImages()->pluck('id')->toArray();


            if ($request->has('existing_images')) {
                $existingImageIds = $request->input('existing_images');

                $imagesToDelete = array_diff($currentImageIds, $existingImageIds);
                $imagesToDeleteModels = ProductImage::whereIn('id', $imagesToDelete)->get();

                foreach ($imagesToDeleteModels as $image) {
                    Storage::disk('public')->delete($image->file_path);
                    $image->delete();
                }
            } else {
                $imagesToDeleteModels = ProductImage::whereIn('id', $currentImageIds)->get();

                foreach ($imagesToDeleteModels as $image) {
                    Storage::disk('public')->delete($image->file_path);
                    $image->delete();
                }
            }

            if ($request->hasFile('new_images')) {
                // dd($request->new_images);
                foreach ($request->file('new_images') as $index => $image) {
                    $fileName = $product->id . '_' . time() . '_' . $index . '.' . $image->getClientOriginalExtension();
                    $filePath = $image->storeAs('products/images', $fileName, 'public');

                    ProductImage::create([
                        'product_id' => $product->id,
                        'file_name' => $fileName,
                        'file_path' => $filePath,
                    ]);
                }
            }

            // if ($request->has('non_availabile_dates')) {
            //     ProductDisableDate::where('product_id', $product->id)->delete();
            //     dd($request->non_availabile_dates);
            //    foreach($request->non_availabile_dates as $dateRange){
            //         // $dateRange = $request->non_available_dates;
            //         dd(array_map('trim', explode(' - ', $dateRange)));
            //         list($startDateStr, $endDateStr) = array_map('trim', explode(' - ', $dateRange));
            //         $startDate = Carbon::parse($startDateStr);;
            //         $endDate = Carbon::parse($endDateStr);;

            //         // Insert selected date range
            //         for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            //             ProductDisableDate::create([
            //                 'product_id' => $product->id,
            //                 'disable_date' => $date->format('Y-m-d'),
            //             ]);
            //         }
            //     }
            // }
            // date range or single date
            if ($request->has('non_availabile_dates')) {
                // Delete existing disabled dates for this product
                ProductDisableDate::where('product_id', $product->id)->delete();

                // Loop through each date or date range in the request
                foreach ($request->non_availabile_dates as $dateRange) {

                    // Check if the current entry is a date range or a single date
                    if (strpos($dateRange, ' - ') !== false) {
                        // This is a date range, so split it
                        list($startDateStr, $endDateStr) = array_map('trim', explode(' - ', $dateRange));
                        $startDate = Carbon::parse($startDateStr);
                        $endDate = Carbon::parse($endDateStr);

                        // Insert each date in the range into the database
                        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                            $data =  ProductDisableDate::create([
                                'product_id' => $product->id,
                                'disable_date' => $date->format('Y-m-d'),
                                'added_by' => auth()->id(),

                            ]);
                        }
                    } else {
                        // This is a single date, process it directly
                        $singleDate = Carbon::parse($dateRange);

                        // Insert the single date into the database
                        ProductDisableDate::create([
                            'product_id' => $product->id,
                            'disable_date' => $singleDate->format('Y-m-d'),
                            'added_by' => auth()->id(),
                        ]);
                    }
                }
            }

            $product->locations()->update([
                'product_id' => $product->id,
                'address1' => $request->address1,
                'address2' => $request->address2,
                'manul_pickup_location' => $request->manual_location ? '1' : '0',
                'country' => $request->country,
                'state' => $request->state,
                'city' => $request->city ?? null,
                'pick_up_location' => $request->product_complete_location,
                // 'product_complete_location' => $request->product_complete_location,
                'raw_address' => $formatted_address ?? null,
                'shipment' => $request->shipment  ? '1' : '0',
            ]);
            return redirect()->route('product')->with('success', 'Product updated successfully.');
            // return redirect()->back()->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function getProduct($id)
    {
        if ($id)
            return Product::with(['locations', 'allImages', 'thumbnailImage', 'nonAvailableDates', 'get_size', 'favorites', 'category'])->whereId(jsdecode_userdata($id))->first();
        return null;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy(Request $request, $id)
    // {
    //     // dd("cvdcsdhcsdh", $request, $id);
    //     $product = $this->getProduct($id);
    //     $getimage = ProductImage::where('product_id', $product->id)->get();

    //     foreach ($getimage as $image) {

    //         if ($image) {
    //             Storage::disk('s3')->delete('products/images/' . $image->file);
    //             $image->delete();
    //         }
    //     }
    //     $product->delete();
    //     if (isset($request->text)) {

    //         // session()->flash('success', __('product.messages.deleteProduct'));

    //         return response()->json([
    //             'success'    =>  true,
    //         ], 200);
    //     }

    //     return redirect()->route('product')->with('success', __('product.messages.deleteProduct'));
    // }
    public function destroy(Request $request, $id)
    {
        try {
            $product = Product::findOrFail(jsdecode_userdata($id));

            // foreach ($product->allImages as $image) {
            //     Storage::disk('public')->delete($image->file_path);
            //     $image->delete();
            // }
            // $product->locations()->delete();



            $product->update(['deleted_at' => carbon::now()]);

            return redirect()->back()->with('success', "Product and associated data have been deleted successfully.");
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function destroy_product(Request $request, $id)
    {
        try {
            // dd('herer');
            $product =  Product::with(['locations', 'allImages', 'thumbnailImage', 'nonAvailableDates', 'get_size', 'favorites', 'category'])->whereId($id)->first();

            $getimage = ProductImage::where('product_id', $product->id)->get();

            foreach ($getimage as $image) {

                if ($image) {
                    Storage::disk('s3')->delete('products/images/' . $image->file);
                    $image->delete();
                }
            }
            $product->delete();
            session()->flash('success', __('product.messages.deleteProduct'));

            return response()->json([
                'success'    =>  true,
            ], 200);
            // dd('dsfsdf');

            // if (session()->has('productSession')) {
            //     $id = session()->get('productSession')->id;
            //     // dd($id);
            //     $product = Product::with(['locations', 'allImages', 'thumbnailImage', 'nonAvailableDates', 'get_size', 'favorites', 'category'])->whereId($id)->first();
            //     // dd($product);
            //     $product->delete();
            //     session()->forget('productSession');
            //     return response()->json([
            //         'success'    =>  true,
            //     ], 200);
            // }
        } catch (\Exception $e) {
            // session()->forget('productSession');
            return response()->json([
                'success'    =>  false,
                'msg'      =>  $e->getMessage()
            ]);
        }
    }

    public function status(Product $product)
    {
        if ('Inactive' == $product->status && 'Admin' == $product->modified_user_type) {
            return response()->json(["error" => 'Status cannot be changed. Please contact to site admin'], 403);
        }

        $status = ($product->status == 'Active') ? 'Inactive' : 'Active';
        $product->update(['status' => $status]);

        return response()->json(['title' => 'Success', 'message' => 'Status changed successfully']);
    }
}
