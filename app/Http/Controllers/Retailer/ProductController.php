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
    // public function store(Request $request)
    // {
    //     // dd("save product : ",$request->images);

    //     // dd("Here",$request->all());
    //     try {

    //         $userId = auth()->user()->id;

    //         $data = [
    //             'name' => $request->product_name,
    //             'user_id' => $userId,
    //             'category_id' => $request->category,
    //             'subcat_id' => $request->subcategory,
    //             'size' => $request->size ?? '',
    //             'color' => $request->color ?? '',
    //             'brand' => $request->brand ?? '',
    //             'condition' => $request->product_condition,
    //             'state' => $request->state,
    //             'city' => $request->city,
    //             'product_market_value' => $request->product_market_value,
    //             'product_link' => $request->product_link,
    //             'min_days_rent_item' => $request->min_rent_days,
    //             'description' => $request->description,
    //             'rent_day' => $request->rent_price_day,
    //             'rent_week' => $request->rent_price_week,
    //             'rent_mont' => $request->rent_price_month,

    //         ];

    //         $product = Product::create($data);
    //         // if ($request->other_brand) {
    //         //     $brand = Brand::where('name', strtolower($request->other_brand))->first();
    //         //     if (!$brand) {
    //         //         $brand = Brand::create(['name' => $request->other_brand]);
    //         //     }
    //         //     $data['brand'] = $brand->id;
    //         // }


    //         if ($request->hasFile('images')) {
    //             foreach ($request->file('images') as $index => $image) {
    //                 // Generate a unique file name
    //                 $fileName = $product->id . '_' . time() . '_' . $index . '.' . $image->getClientOriginalExtension();
    //                 $filePath = $image->storeAs('products/images', $fileName, 'public');

    //                 ProductImage::create([
    //                     'product_id' => $product->id,
    //                     'file_name' => $fileName,
    //                     'file_path' => $filePath,
    //                 ]);
    //             }
    //         }



    //         if ($request->other_size) {
    //             $data['size'] = null;
    //         }


    //         ProductLocation::create([
    //             'product_id' => $product->id,
    //             'map_address' => $request->pick_up_location,
    //         ]);


    //         dd("YES DONE");
    //         // session()->flash('success', "Your product has been uploaded successfully.");
    //         return redirect()->back()-> session()->flash('success', "Your product has been uploaded successfully.");
    //     } catch (\Exception $e) {
    //         return redirect()->back()-> session()->flash('error', $e->getMessage());
    //     }
    // }

    public function store(StoreProductRequest $request)
    {

        // dd($request->all());
        try {
            DB::beginTransaction();

            $product_complete_location = $request->input('product_complete_location');
            $address = urlencode($product_complete_location);

            $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=" . config('services.google_maps.api_key');
            $response = file_get_contents($url);
            $raw_address = json_decode($response, true);

            if (!empty($raw_address['results'])) {
                $formatted_address = json_encode($raw_address['results'][0]);
            }


            $userId = auth()->user()->id;

            $data = [
                'name' => $request->product_name,
                'description' => $request->description,
                'user_id' => $userId,
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
                'status' => '1',
                'modified_by' => $userId,
                'modified_user_type' => 'Self',
                'non_available_dates' => $request->non_available_dates ?? 0,
            ];

            $product = Product::create($data);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $fileName = $product->id . '_' . time() . '_' . $index . '.' . $image->getClientOriginalExtension();
                    $filePath = $image->storeAs('products/images', $fileName, 'public');

                    // dd($fileName,$filePath);
                    ProductImage::create([
                        'product_id' => $product->id,
                        'file_name' => $fileName,
                        'file_path' => $filePath,
                    ]);
                }
            }

            ProductLocation::create([
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
                'postcode'=>$request->zipcode,
            ]);



            if ($request->has('non_available_dates') && $request->filled('non_available_dates')) {
                $dateRange = $request->non_available_dates;
                list($startDateStr, $endDateStr) = explode(' - ', $dateRange);
                $startDate = Carbon::createFromFormat('Y-m-d', $startDateStr);
                $endDate = Carbon::createFromFormat('Y-m-d', $endDateStr);

                for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                    ProductDisableDate::create([
                        'product_id' => $product->id,
                        'disable_date' => $date->format('Y-m-d'),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('product')->with('success', "Your product has been uploaded successfully.");

            // return redirect()->back()->with('success', "Your product has been uploaded successfully.");
        } catch (\Exception $e) {
            // dd($e->getMessage());
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
    // public function edit($id)
    // {

    //     $product = $this->getProduct($id);
    //     // dd("The category id is : ",$product->category_id);
    //     $sub_categories = Category::where('status', '1')->where('parent_id', $product->category_id)->get();
    //     $subcatId = ($product->subcat_id) ? $product->subcat_id : '';

    //     $category = Category::with(['size_type'])->where('status', '1')->where('id', $product->category_id)->first();
    //     $types = isset($category) && count($category->size_type) > 0 ? $category->size_type : [];
    //     $selectedType = ($product->get_size) ? $product->get_size->type : '';

    //     $sizes = Size::where('status', '1')->where('type', $selectedType)->get();
    //     $selectedSize = isset($product) ? $product->size : '';
    //     $other_size = isset($product) ? $product->other_size : '';

    //     $selectedType_Other = ($selectedSize == '') ? 'other' : '';



    //     $data = NeighborhoodCity::where('id', $product->neighborhood_city)->first();
    //     // dd($data);
    //     $neighborhood = NeighborhoodCity::where('parent_id', $data->parent_id)->get();
    //     $pickuplocation = ProductLocation::where('product_id', $product->id)->first();

    //     $selectedneighbor = $product->neighborhood_city ? $product->neighborhood_city  : '';
    //     return response()->json([
    //         'status'    =>  true,
    //         'data'      =>  [
    //             'html'  =>  view('retailer.include.product-from', compact('product'))->render(),
    //             'subcat'  =>  view('retailer.include.subcategory', compact('sub_categories', 'subcatId'))->render(),
    //             'type'    => view('retailer.include.type', compact('types', 'selectedType', 'selectedType_Other'))->render(),
    //             'size'    => view('retailer.include.size', compact('sizes', 'selectedSize', 'other_size'))->render(),
    //             'neighborhoodcity' => view('retailer.include.neighborhoodcity', compact('neighborhood', 'selectedneighbor'))->render(),
    //             'other' => $selectedType_Other,
    //         ]
    //     ]);
    // }

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


        $disabledDates = $product->disableDates;

        // dd("here",$disabledDates);
        if ($disabledDates->isNotEmpty()) {
            $sortedDates = $disabledDates->sortBy('disable_date');
            $firstDate = \Carbon\Carbon::parse($sortedDates->first()->disable_date)->format('Y-m-d');
            $lastDate = \Carbon\Carbon::parse($sortedDates->last()->disable_date)->format('Y-m-d');

            $formattedDates = $firstDate . ' - ' . $lastDate;
        } else {
            $formattedDates = '';
        }

        return view('customer.update_product', compact('product', 'sub_categories', 'category', 'sizes', 'city', 'state', 'pickuplocation','formattedDates'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(UpdateProductRequest $request, $id)
    // {
    //     dd($request->all());
    //     try {
    //         $userId = auth()->user()->id;
    //         $is_bankdetail = auth()->user()->vendorBankDetails;
    //         $checkdocuments = count(auth()->user()->documents);
    //         $product = $this->getProduct($id);
    //         // dd($product);
    //         $data = [
    //             'name' => $request->name,
    //             'description' => $request->description,
    //             'rentaltype' => $request->rentaltype,
    //             'category_id' => jsdecode_userdata($request->category),
    //             'subcat_id' => jsdecode_userdata($request->sub_cat),
    //             'size' => $request->size,
    //             'other_size' => $request->other_size,
    //             'color' => $request->color,
    //             'condition' => $request->product_condition,
    //             'user_id' => $userId,
    //             'rent' => $request->rent,
    //             'price' => $request->price,
    //             'status' => isset($request->status) ? '1' : '0',
    //             'product_market_value' => $request->product_market_value,
    //             'product_link' => $request->product_link,
    //             'min_days_rent_item' => $request->min_rent_days,
    //         ];

    //         if ($request->neighborhoodcity && $request->neighborhood) {
    //             $data['city'] = $request->neighborhoodcity;
    //             $data['neighborhood_city'] = $request->neighborhood;
    //         }

    //         if ($request->other_size) {
    //             $data['size'] = null;
    //         }

    //         if ('1' == $data['status'] && 'Admin' == $product->modified_user_type) {
    //             unset($data['status']);
    //             unset($data['modified_user_type']);
    //             unset($data['modified_by']);
    //         }

    //         //$removedImageIds = explode(',', $request->removed_images);
    //         $product->update($data);
    //         if (isset($_COOKIE['img_token'])) {
    //             ProductImage::whereImageToken($_COOKIE['img_token'])->update(['product_id' => $product->id, 'image_token' => null]);
    //         }

    //         if ($request->pickup_location) {
    //             Schema::disableForeignKeyConstraints();
    //             ProductLocation::where('product_id', $product->id)->delete();
    //             Schema::enableForeignKeyConstraints();

    //             ProductLocation::insert([
    //                 'product_id' => $product->id,
    //                 'map_address' => $request->pickup_location,
    //             ]);
    //         }


    //         if ($request->has('locations')) {
    //             $locationData = [];
    //             $locations = $request->locations;
    //             foreach ($locations['value'] as $index => $location) {
    //                 $locationData[] = [
    //                     'product_id' => $product->id,
    //                     'map_address' => $location,
    //                     'country' => $locations['country'][$index],
    //                     'state' => $locations['state'][$index],
    //                     'city' => $locations['city'][$index],
    //                     'latitude' => $locations['latitude'][$index],
    //                     'longitude' => $locations['longitude'][$index],
    //                     'postcode' => $locations['postal_code'][$index],
    //                     'custom_address' => $locations['custom'][$index],
    //                 ];
    //             }
    //             Schema::disableForeignKeyConstraints();
    //             ProductLocation::where('product_id', $product->id)->delete();
    //             Schema::enableForeignKeyConstraints();
    //             if (count($locationData)) {
    //                 ProductLocation::insert($locationData);
    //             }
    //         }


    //         if ($request->has('non_availabile_dates') && count($request->non_availabile_dates)) {

    //             $nonAvailableDates = $request->non_availabile_dates;
    //             $nonAvailableDatesArray = [];
    //             $productUnavailability = [];

    //             if ($request->rentaltype == 'Day') {
    //                 foreach ($nonAvailableDates as $nonAvailableDate) {
    //                     if (!is_null($nonAvailableDate)) {
    //                         $fromAndToDate = array_map('trim', explode($request->global_date_separator, $nonAvailableDate));
    //                         // $fromstartDate = date($request->global_date_format_for_check, strtotime($fromAndToDate[0]));
    //                         // $fromendDate = date($request->global_date_format_for_check, strtotime($fromAndToDate[1]));
    //                         $nonAvailableDatesArray = array_merge($fromAndToDate, $nonAvailableDatesArray);
    //                     }
    //                 }
    //                 $unavailabilityLength = count($nonAvailableDatesArray);
    //                 for ($i = 0; $i < $unavailabilityLength; $i += 2) {
    //                     $productUnavailability[] = [
    //                         'product_id' => $product->id,
    //                         'from_date' => DateTime::createFromFormat('m/d/Y', $nonAvailableDatesArray[$i])->format('Y-m-d'),
    //                         'to_date' => DateTime::createFromFormat('m/d/Y', $nonAvailableDatesArray[$i + 1])->format('Y-m-d'),
    //                     ];
    //                 }
    //             } else {
    //                 foreach ($nonAvailableDates as $nonAvailableDate) {
    //                     if (!is_null($nonAvailableDate)) {
    //                         $fromAndToDate = array_map('trim', explode($request->global_date_separator, $nonAvailableDate));
    //                         $nonAvailableDatesArray = array_merge($nonAvailableDatesArray, $fromAndToDate);
    //                     }
    //                 }
    //                 $unavailabilityLength = count($nonAvailableDatesArray);
    //                 for ($i = 0; $i < $unavailabilityLength; $i += 2) {
    //                     $productUnavailability[] = [
    //                         'product_id' => $product->id,
    //                         'from_date' => DateTime::createFromFormat($request->global_product_date_time_format, $nonAvailableDatesArray[$i])->format('Y-m-d'),
    //                         'to_date' => DateTime::createFromFormat($request->global_product_date_time_format, $nonAvailableDatesArray[$i + 1])->format('Y-m-d'),
    //                         'from_hour' => DateTime::createFromFormat($request->global_product_date_time_format, $nonAvailableDatesArray[$i])->format('H'),
    //                         'from_minute' => DateTime::createFromFormat($request->global_product_date_time_format, $nonAvailableDatesArray[$i])->format('i'),
    //                         'to_hour' => DateTime::createFromFormat($request->global_product_date_time_format, $nonAvailableDatesArray[$i + 1])->format('H'),
    //                         'to_minute' => DateTime::createFromFormat($request->global_product_date_time_format, $nonAvailableDatesArray[$i + 1])->format('i'),
    //                     ];
    //                 }
    //             }

    //             ProductUnavailability::where('product_id', $product->id)->delete();
    //             if (count($productUnavailability)) {
    //                 ProductUnavailability::insert($productUnavailability);
    //             }
    //         }

    //         $url = route('product');
    //         session()->flash('success', __('product.messages.updateProduct'));
    //         return response()->json([
    //             'success'    =>  true,
    //             'url'       =>   $url,
    //             'is_bankdetail' => $is_bankdetail,
    //             'checkdocuments' => $checkdocuments
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success'    =>  false,
    //             'msg'      =>  $e->getMessage()
    //         ]);
    //     }
    // }

    public function update(UpdateProductRequest $request, $id)
    {
        // dd("here",$request->all());
        // dd("ID Is: ",jsdecode_userdata($id));
        // if ($request->hasFile('new_images')) {
        //     dd($request->new_images);
        //     dd('SLECTED');
        // }

        // dd("NOt slected");

        $product_complete_location = $request->input('product_complete_location');
        $address = urlencode($product_complete_location);

        $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=" . config('services.google_maps.api_key');
        $response = file_get_contents($url);
        $raw_address = json_decode($response, true);

        if (!empty($raw_address['results'])) {
            $formatted_address = json_encode($raw_address['results'][0]);
        }

        try {
            $product = Product::findOrFail(jsdecode_userdata($id));
            $userId = auth()->user()->id;

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
            ];

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

            if ($request->has('non_available_dates')) {
                ProductDisableDate::where('product_id', $product->id)->delete();

                $dateRange = $request->non_available_dates;

                if (!empty($dateRange)) {
                    list($startDateStr, $endDateStr) = explode(' - ', $dateRange);
                    $startDate = Carbon::createFromFormat('Y-m-d', $startDateStr);
                    $endDate = Carbon::createFromFormat('Y-m-d', $endDateStr);

                    for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                        ProductDisableDate::create([
                            'product_id' => $product->id,
                            'disable_date' => $date->format('Y-m-d'),
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
            ]);
            return redirect()->route('product')->with('success', 'Product updated successfully.');
            // return redirect()->back()->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
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

            foreach ($product->allImages as $image) {
                Storage::disk('public')->delete($image->file_path);
                $image->delete();
            }
            $product->locations()->delete();



            $product->update(['deleted_at'=>carbon::now()]);

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
