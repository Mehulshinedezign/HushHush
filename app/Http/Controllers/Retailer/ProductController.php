<?php

namespace App\Http\Controllers\Retailer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\{Category, Product, ProductImage, ProductLocation, ProductUnavailability, Brand, RetailerBankInformation, Size, User, NeighborhoodCity};
use Carbon\Carbon;
use DateTime, Stripe;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;

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
    public function store(Request $request)
    {
        //dd(request()->cookie('img_token'));
        //dd("product store method", $request->toArray());

        // dd("Here",$request->all());
        try {

            $userId = auth()->user()->id;
            $is_bankdetail = auth()->user()->vendorBankDetails;
            $checkdocuments = count(auth()->user()->documents);
            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'rentaltype' => $request->rentaltype,
                'category_id' => jsdecode_userdata($request->category),
                'subcat_id' => jsdecode_userdata($request->sub_cat),
                'size' => $request->size,
                'other_size' => $request->other_size,
                'color' => $request->color,
                'brand' => $request->brand,
                'condition' => $request->product_condition,
                'user_id' => $userId,
                'rent' => $request->rent,
                'price' => $request->price,
                'status' => $is_bankdetail ? isset($request->status) ? '1' : '0' : '0',
                'product_market_value' => $request->product_market_value,
                'product_link' => $request->product_link,
            ];
            if ($request->neighborhoodcity && $request->neighborhood) {
                $data['city'] = $request->neighborhoodcity;
                $data['neighborhood_city'] = $request->neighborhood;
            }

            if ($request->other_brand) {
                $brand = Brand::where('name', strtolower($request->other_brand))->first();
                if (!$brand) {
                    $brand = Brand::create(['name' => $request->other_brand]);
                }
                $data['brand'] = $brand->id;
            }

            if ($request->other_size) {
                $data['size'] = null;
            }

            $product = Product::create($data);

            if (isset($_COOKIE['img_token'])) {
                ProductImage::whereImageToken($_COOKIE['img_token'])->update(['product_id' => $product->id, 'image_token' => null]);
            }

            $neighborhoodcity = NeighborhoodCity::where('id', $request->neighborhood)->first();
            ProductLocation::create([
                'product_id' => $product->id,
                'map_address' => $neighborhoodcity->name,
            ]);

            if ($request->has('locations')) {
                $locationData = [];
                $locations = $request->locations;
                foreach ($locations['value'] as $index => $location) {
                    $locationData[] = [
                        'product_id' => $product->id,
                        'map_address' => $location,
                        'country' => $locations['country'][$index],
                        'state' => $locations['state'][$index],
                        'city' => $locations['city'][$index],
                        'latitude' => $locations['latitude'][$index],
                        'longitude' => $locations['longitude'][$index],
                        'postcode' => $locations['postal_code'][$index],
                        'custom_address' => $locations['custom'][$index],
                    ];
                }
                // dd($locationData);
                if (count($locationData)) {
                    ProductLocation::insert($locationData);
                }
            }
            if ($request->has('non_availabile_dates') && count($request->non_availabile_dates)) {
                // dd($request->non_availabile_dates);

                $nonAvailableDates = $request->non_availabile_dates;
                $nonAvailableDatesArray = [];
                $productUnavailability = [];

                if ($request->rentaltype == 'Day') {
                    foreach ($nonAvailableDates as $nonAvailableDate) {
                        if (!is_null($nonAvailableDate)) {
                            $fromAndToDate = array_map('trim', explode($request->global_date_separator, $nonAvailableDate));
                            // $fromstartDate = date($request->global_date_format_for_check, strtotime($fromAndToDate[0]));
                            // $fromendDate = date($request->global_date_format_for_check, strtotime($fromAndToDate[1]));
                            $nonAvailableDatesArray = array_merge($fromAndToDate, $nonAvailableDatesArray);
                        }
                    }

                    $unavailabilityLength = count($nonAvailableDatesArray);
                    for ($i = 0; $i < $unavailabilityLength; $i += 2) {
                        $productUnavailability[] = [
                            'product_id' => $product->id,
                            'from_date' => DateTime::createFromFormat('m/d/Y', $nonAvailableDatesArray[$i])->format('Y-m-d'),
                            'to_date' => DateTime::createFromFormat('m/d/Y', $nonAvailableDatesArray[$i + 1])->format('Y-m-d'),
                        ];
                    }
                } else {
                    foreach ($nonAvailableDates as $nonAvailableDate) {
                        if (!is_null($nonAvailableDate)) {
                            $fromAndToDate = array_map('trim', explode($request->global_date_separator, $nonAvailableDate));
                            $nonAvailableDatesArray = array_merge($nonAvailableDatesArray, $fromAndToDate);
                        }
                    }
                    $unavailabilityLength = count($nonAvailableDatesArray);
                    for ($i = 0; $i < $unavailabilityLength; $i += 2) {
                        $productUnavailability[] = [
                            'product_id' => $product->id,
                            'from_date' => DateTime::createFromFormat($request->global_product_date_time_format, $nonAvailableDatesArray[$i])->format('Y-m-d'),
                            'to_date' => DateTime::createFromFormat($request->global_product_date_time_format, $nonAvailableDatesArray[$i + 1])->format('Y-m-d'),
                            'from_hour' => DateTime::createFromFormat($request->global_product_date_time_format, $nonAvailableDatesArray[$i])->format('H'),
                            'from_minute' => DateTime::createFromFormat($request->global_product_date_time_format, $nonAvailableDatesArray[$i])->format('i'),
                            'to_hour' => DateTime::createFromFormat($request->global_product_date_time_format, $nonAvailableDatesArray[$i + 1])->format('H'),
                            'to_minute' => DateTime::createFromFormat($request->global_product_date_time_format, $nonAvailableDatesArray[$i + 1])->format('i'),
                        ];
                    }
                }
                if (count($productUnavailability)) {
                    ProductUnavailability::insert($productUnavailability);
                }
            }
            $getId =  jsencode_userdata($product->id);
            // if (!request()->ajax()) {
            //     if (!$is_bankdetail) {
            //     }
            //     return redirect()->route('index');
            // }
            // $url = route('products');
            session()->flash('success', "Your product has been uploaded successfully.");
            return response()->json([
                'success'    =>  true,
                // 'url'       =>   $url
                'product' => $getId,
                'is_bankdetail' => $is_bankdetail,
                'checkdocuments' => $checkdocuments
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success'    =>  false,
                'msg'      =>  $e->getMessage()
            ]);
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
        $product = $this->getProduct($id);
        $sub_categories = Category::where('status', '1')->where('parent_id', $product->category_id)->get();
        $subcatId = ($product->subcat_id) ? $product->subcat_id : '';

        $category = Category::with(['size_type'])->where('status', '1')->where('id', $product->category_id)->first();
        $types = isset($category) && count($category->size_type) > 0 ? $category->size_type : [];
        $selectedType = ($product->get_size) ? $product->get_size->type : '';

        $sizes = Size::where('status', '1')->where('type', $selectedType)->get();
        $selectedSize = isset($product) ? $product->size : '';
        $other_size = isset($product) ? $product->other_size : '';

        $selectedType_Other = ($selectedSize == '') ? 'other' : '';



        $data = NeighborhoodCity::where('id', $product->neighborhood_city)->first();

        $neighborhood = NeighborhoodCity::where('parent_id', $data->parent_id)->get();
        $pickuplocation = ProductLocation::where('product_id', $product->id)->first();

        $selectedneighbor = $product->neighborhood_city ? $product->neighborhood_city  : '';
        return response()->json([
            'status'    =>  true,
            'data'      =>  [
                'html'  =>  view('retailer.include.product-from', compact('product'))->render(),
                'subcat'  =>  view('retailer.include.subcategory', compact('sub_categories', 'subcatId'))->render(),
                'type'    => view('retailer.include.type', compact('types', 'selectedType', 'selectedType_Other'))->render(),
                'size'    => view('retailer.include.size', compact('sizes', 'selectedSize', 'other_size'))->render(),
                'neighborhoodcity' => view('retailer.include.neighborhoodcity', compact('neighborhood', 'selectedneighbor'))->render(),
                'other' => $selectedType_Other,
            ]
        ]);
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
        //dd($request->all());
        try {
            $userId = auth()->user()->id;
            $is_bankdetail = auth()->user()->vendorBankDetails;
            $checkdocuments = count(auth()->user()->documents);
            $product = $this->getProduct($id);
            // dd($product);
            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'rentaltype' => $request->rentaltype,
                'category_id' => jsdecode_userdata($request->category),
                'subcat_id' => jsdecode_userdata($request->sub_cat),
                'size' => $request->size,
                'other_size' => $request->other_size,
                'color' => $request->color,
                'condition' => $request->product_condition,
                'user_id' => $userId,
                'rent' => $request->rent,
                'price' => $request->price,
                'status' => isset($request->status) ? '1' : '0'
            ];

            if ($request->neighborhoodcity && $request->neighborhood) {
                $data['city'] = $request->neighborhoodcity;
                $data['neighborhood_city'] = $request->neighborhood;
            }

            if ($request->other_size) {
                $data['size'] = null;
            }

            if ('1' == $data['status'] && 'Admin' == $product->modified_user_type) {
                unset($data['status']);
                unset($data['modified_user_type']);
                unset($data['modified_by']);
            }

            //$removedImageIds = explode(',', $request->removed_images);
            $product->update($data);
            if (isset($_COOKIE['img_token'])) {
                ProductImage::whereImageToken($_COOKIE['img_token'])->update(['product_id' => $product->id, 'image_token' => null]);
            }
            //$productMeta = [];
            //$thumbnail = $request->thumbnail_image;
            // for ($i = 1; $i <= $request->global_max_product_image_count; $i++) {
            //     if ($request->hasFile('image' . $i)) {
            //         $image = s3_store_image($request->file('image' . $i), 'products/images');
            //         if ($image != null) {
            //             $productMeta[] = [
            //                 'product_id' => $product->id,
            //                 'file' => $image['name'],
            //                 'url' => $image['url'],
            //                 'type' => ($thumbnail == $i) ? 'thumbnail' : 'gallery'
            //             ];
            //         }
            //         // $file = $request->file('image' . $i);
            //         // $path = $file->store('products', 's3');
            //         // $url = Storage::disk('s3')->url($path);
            //         // $productMeta[] = [
            //         //     'product_id' => $product->id,
            //         //     'file' => basename($path),
            //         //     'url' => $url,
            //         //     'type' => ($thumbnail == $i) ? 'thumbnail' : 'gallery'
            //         // ];
            //     }
            // }

            // if (count($productMeta) || count($removedImageIds)) {
            //     $productImages = ProductImage::where('product_id', $product->id)->whereIn('id', $removedImageIds)->get();
            //     foreach ($productImages as $productImage) {
            //         Storage::disk('s3')->delete('products/images/' . $productImage->file);
            //         // $productImage->delete();
            //     }

            //     if (count($productMeta)) {

            //         ProductImage::insert($productMeta);
            //     }
            // }

            if ($request->pickup_location) {
                Schema::disableForeignKeyConstraints();
                ProductLocation::where('product_id', $product->id)->delete();
                Schema::enableForeignKeyConstraints();

                ProductLocation::insert([
                    'product_id' => $product->id,
                    'map_address' => $request->pickup_location,
                ]);
            }


            if ($request->has('locations')) {
                $locationData = [];
                $locations = $request->locations;
                foreach ($locations['value'] as $index => $location) {
                    $locationData[] = [
                        'product_id' => $product->id,
                        'map_address' => $location,
                        'country' => $locations['country'][$index],
                        'state' => $locations['state'][$index],
                        'city' => $locations['city'][$index],
                        'latitude' => $locations['latitude'][$index],
                        'longitude' => $locations['longitude'][$index],
                        'postcode' => $locations['postal_code'][$index],
                        'custom_address' => $locations['custom'][$index],
                    ];
                }
                Schema::disableForeignKeyConstraints();
                ProductLocation::where('product_id', $product->id)->delete();
                Schema::enableForeignKeyConstraints();
                if (count($locationData)) {
                    ProductLocation::insert($locationData);
                }
            }


            if ($request->has('non_availabile_dates') && count($request->non_availabile_dates)) {

                $nonAvailableDates = $request->non_availabile_dates;
                $nonAvailableDatesArray = [];
                $productUnavailability = [];

                if ($request->rentaltype == 'Day') {
                    foreach ($nonAvailableDates as $nonAvailableDate) {
                        if (!is_null($nonAvailableDate)) {
                            $fromAndToDate = array_map('trim', explode($request->global_date_separator, $nonAvailableDate));
                            // $fromstartDate = date($request->global_date_format_for_check, strtotime($fromAndToDate[0]));
                            // $fromendDate = date($request->global_date_format_for_check, strtotime($fromAndToDate[1]));
                            $nonAvailableDatesArray = array_merge($fromAndToDate, $nonAvailableDatesArray);
                        }
                    }
                    $unavailabilityLength = count($nonAvailableDatesArray);
                    for ($i = 0; $i < $unavailabilityLength; $i += 2) {
                        $productUnavailability[] = [
                            'product_id' => $product->id,
                            'from_date' => DateTime::createFromFormat('m/d/Y', $nonAvailableDatesArray[$i])->format('Y-m-d'),
                            'to_date' => DateTime::createFromFormat('m/d/Y', $nonAvailableDatesArray[$i + 1])->format('Y-m-d'),
                        ];
                    }
                } else {
                    foreach ($nonAvailableDates as $nonAvailableDate) {
                        if (!is_null($nonAvailableDate)) {
                            $fromAndToDate = array_map('trim', explode($request->global_date_separator, $nonAvailableDate));
                            $nonAvailableDatesArray = array_merge($nonAvailableDatesArray, $fromAndToDate);
                        }
                    }
                    $unavailabilityLength = count($nonAvailableDatesArray);
                    for ($i = 0; $i < $unavailabilityLength; $i += 2) {
                        $productUnavailability[] = [
                            'product_id' => $product->id,
                            'from_date' => DateTime::createFromFormat($request->global_product_date_time_format, $nonAvailableDatesArray[$i])->format('Y-m-d'),
                            'to_date' => DateTime::createFromFormat($request->global_product_date_time_format, $nonAvailableDatesArray[$i + 1])->format('Y-m-d'),
                            'from_hour' => DateTime::createFromFormat($request->global_product_date_time_format, $nonAvailableDatesArray[$i])->format('H'),
                            'from_minute' => DateTime::createFromFormat($request->global_product_date_time_format, $nonAvailableDatesArray[$i])->format('i'),
                            'to_hour' => DateTime::createFromFormat($request->global_product_date_time_format, $nonAvailableDatesArray[$i + 1])->format('H'),
                            'to_minute' => DateTime::createFromFormat($request->global_product_date_time_format, $nonAvailableDatesArray[$i + 1])->format('i'),
                        ];
                    }
                }

                ProductUnavailability::where('product_id', $product->id)->delete();
                if (count($productUnavailability)) {
                    ProductUnavailability::insert($productUnavailability);
                }
            }

            $url = route('product');
            session()->flash('success', __('product.messages.updateProduct'));
            return response()->json([
                'success'    =>  true,
                'url'       =>   $url,
                'is_bankdetail' => $is_bankdetail,
                'checkdocuments' => $checkdocuments
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success'    =>  false,
                'msg'      =>  $e->getMessage()
            ]);
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
    public function destroy(Request $request, $id)
    {
        // dd("cvdcsdhcsdh", $request, $id);
        $product = $this->getProduct($id);
        $getimage = ProductImage::where('product_id', $product->id)->get();

        foreach ($getimage as $image) {

            if ($image) {
                Storage::disk('s3')->delete('products/images/' . $image->file);
                $image->delete();
            }
        }
        $product->delete();
        if (isset($request->text)) {

            // session()->flash('success', __('product.messages.deleteProduct'));

            return response()->json([
                'success'    =>  true,
            ], 200);
        }

        return redirect()->route('product')->with('success', __('product.messages.deleteProduct'));
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
