<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ProductTrait;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\NeighborhoodCity;
use App\Models\Order;
use App\Models\OrderImage;
use App\Models\Product;
use App\Models\ProductDisableDate;
use App\Models\ProductFavorite;
use App\Models\ProductImage;
use App\Models\ProductLocation;
use App\Models\ProductRating;
use App\Models\ProductUnavailability;
use App\Models\Query;
use App\Models\Size;
use App\Models\User;
use App\Notifications\ItemYouLike;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpParser\ErrorHandler\Throwing;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isEmpty;

class ProductController extends Controller
{
    use ProductTrait;

    /**
     * Product listing
     */

    public function index(Request $request)
    {
        // dd("THENNNNN",$request->all(),$request->category);
        try {
            // dd(auth()->user());
            $categories = Category::where('status', true)->get();

            $products = $this->getProducts($request);
            // dd($products);
            $products = $products->paginate($request->global_product_pagination);

            $transformedProducts = $products->map(function ($product) {
                return [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'description' => $product['description'],
                    'specification' => $product['specification'],
                    'rentaltype' => $product['rentaltype'],
                    'user_id' => $product['user_id'],
                    'category_id' => $product['category_id'],
                    'subcat_id' => $product['subcat_id'],
                    'quantity' => $product['quantity'],
                    'rent' => $product['rent'],
                    'min_days_rent_item' => $product['min_days_rent_item'],
                    'price' => $product['price'],
                    'security' => $product['security'],
                    'status' => $product['status'],
                    'other_size' => $product['other_size'],
                    'condition' => $product['condition'],
                    'brand' => $product['brand'],
                    'color' => $product['color'],
                    'size' => $product['size'],
                    'modified_by' => $product['modified_by'],
                    'modified_user_type' => $product['modified_user_type'],
                    'available' => $product['available'],
                    'city' => $product['city'],
                    'neighborhood_city' => $product['neighborhood_city'],
                    'product_market_value' => $product['product_market_value'],
                    'product_link' => $product['product_link'],
                    'average_rating' => $product['average_rating'],
                    'is_favorite' => $product->favorites === null ? false : true,
                    // 'prodcut_image_url' => $product->thumbnailImage->url,
                    'product_image_url' => $product->thumbnailImage && filter_var($product->thumbnailImage->url, FILTER_VALIDATE_URL) ? $product->thumbnailImage->url : null,
                ];
            })->toArray();

            // dd("HERE",$transformedProducts);
            $apiResponse = 'success';
            $statusCode = '200';
            $message = 'Data fetched successfully!';
            // dd($categories);
            return $this->apiResponse($apiResponse, $statusCode, $message, $transformedProducts, $categories);
        } catch (\Throwable $e) {
            $apiResponse = 'error';
            $statusCode = '500';
            $message = $e->getMessage();
            return $this->apiResponse($apiResponse, $statusCode, $message, null, null);
        }
    }


    /**
     * My wishlist product
     */

    public function wishlist(Request $request)
    {
        try {

            $productFavorites = ProductFavorite::with(['product.thumbnailImage', 'product.category'])
                ->where('user_id', auth()->user()->id)
                ->whereHas('product', function ($query) {
                    $query->whereNull('deleted_at');
                })
                ->orderByDesc('id')
                ->paginate($request->global_product_pagination);

            $wishlistProducts = $productFavorites->map(function ($productFavorite) {
                $product = $productFavorite->product;

                if ($product) {
                    // dd('here');
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'description' => $product->description,
                        'specification' => $product->specification,
                        'rentaltype' => $product->rentaltype,
                        'user_id' => $product->user_id,
                        'category_name' => $product->category->name,
                        'subcat_id' => $product->subcat_id,
                        'quantity' => $product->quantity,
                        'rent' => $product->rent,
                        'min_days_rent_item' => $product->min_days_rent_item,
                        'price' => $product->price,
                        'security' => $product->security,
                        'status' => $product->status,
                        'other_size' => $product->other_size,
                        'condition' => $product->condition,
                        'brand' => $product->get_brand->name ?? 'N/A',
                        'color' => $product->get_color->name ?? 'N/A',
                        'size' => $product->get_size->name ?? 'N/A',
                        'modified_by' => $product->modified_by,
                        'modified_user_type' => $product->modified_user_type,
                        'available' => $product->available,
                        'city' => $product->city,
                        'rent_day' => $product->rent_day,
                        'rent_week' => $product->rent_week,
                        'rent_month' => $product->rent_month,
                        // 'neighborhood_city' => $product->neighborhood_city,
                        'product_market_value' => $product->product_market_value,
                        'product_link' => $product->product_link,
                        // 'created_at' => $product->created_at,
                        // 'updated_at' => $product->updated_at,
                        // 'deleted_at' => $product->deleted_at,
                        'average_rating' => $product->average_rating,
                        'product_image_url' => $product->thumbnailImage && filter_var($product->thumbnailImage->file_path, FILTER_VALIDATE_URL) ? $product->thumbnailImage->file_path : null,
                        'favourites' => !is_null($product->favorites) ? true : false,
                    ];
                }

                return null;
            })->filter()->toArray();

            $apiResponse = 'success';
            $statusCode = '200';
            $message = 'Wishlist product fetched successfully!';

            if ($wishlistProducts) {
                // return $this->apiResponse($apiResponse, $statusCode, $message, $wishlistProducts, null);
                return response()->json([
                    'status' => true,
                    'message' => 'Wishlist product fetched successfully!',
                    'data' => $wishlistProducts,
                ], 200);
            } else {
                $data = [];
                // return $this->apiResponse($apiResponse, $statusCode, 'There is no product list add', $data, null);
                return response()->json([
                    'status' => true,
                    'message' => 'There is no product list add!',
                    'data' => $wishlistProducts,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }



    /**
     * Add the product in wish list
     */
    public function addFavorite(Request $request, $id)
    {
        try {

            $product = Product::find($id);
            if (is_null($product)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Product not found',
                    'errors' => ['product_id' => 'The product does not exist.'],
                ], 404);
            }

            $favorite = ProductFavorite::where('user_id', auth()->user()->id)->where('product_id', $id)->first();
            if (is_null($favorite)) {

                ProductFavorite::create([
                    'product_id' => $id,
                    'user_id' => auth()->user()->id
                ]);
                $message = 'Added to wishlist';
            } else {

                $favorite->delete();
                $message = 'Removed from wishlist';
            }


            $product_details = $this->getProduct($id);
            $apiResponse = 'success';
            $statusCode = 200;
            $data = [
                'product_id' => $id,
                'product_details' => $product_details,
            ];
        } catch (\Throwable $e) {
            $apiResponse = 'error';
            $statusCode = 500;
            $data = [];
            $message = 'Failed to update wishlist. Error: ' . $e->getMessage();
        }

        return $this->apiResponse($apiResponse, $statusCode, $message, $data, null);
    }



    /**
     * Remove product from wish list
     */
    public function removeFavorite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productid' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $apiResponse = 'success';
        $statusCode = 200;
        $message = 'Removed from wishlist';

        try {
            $product = ProductFavorite::where('user_id', auth()->user()->id)->where('product_id', $request->productid)->first();

            if ($product) {
                $product->delete();
            }
        } catch (\Throwable $e) {
            $apiResponse = 'error';
            $statusCode = 500;
            $message = 'Failed to remove from wishlist. Error: ' . $e->getMessage();
        }

        return $this->apiResponse($apiResponse, $statusCode, $message, null, null);
    }

    /**
     * Provide a product category list.
     */

    public function category(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'category' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $category_products = Category::with('products')->where('id', $request->category)->get();

            $category_product = $category_products->map(function ($category) {
                return $category->products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'description' => $product->description,
                        'specification' => $product->specification,
                        'rentaltype' => $product->rentaltype,
                        'user_id' => $product->user_id,
                        'category_id' => $product->category_id,
                        'subcat_id' => $product->subcat_id,
                        'quantity' => $product->quantity,
                        'rent' => $product->rent,
                        'min_days_rent_item' => $product->min_days_rent_item,
                        'price' => $product->price,
                        'security' => $product->security,
                        'status' => $product->status,
                        'other_size' => $product->other_size,
                        'condition' => $product->condition,
                        'brand' => $product->brand,
                        'color' => $product->color,
                        'size' => $product->size,
                        'modified_by' => $product->modified_by,
                        'modified_user_type' => $product->modified_user_type,
                        'available' => $product->available,
                        'city' => $product->city,
                        'neighborhood_city' => $product->neighborhood_city,
                        'product_market_value' => $product->product_market_value,
                        'product_link' => $product->product_link,
                        'created_at' => $product->created_at,
                        'updated_at' => $product->updated_at,
                        'deleted_at' => $product->deleted_at,
                        'average_rating' => $product->average_rating,
                    ];
                });
            })->toArray();

            $apiResponse = 'success';
            $statusCode = 200;
            $message = 'Category product fetched successfully!';
        } catch (\Throwable $e) {
            $apiResponse = 'error';
            $statusCode = 500;
            $message = $e->getMessage();
        }

        return $this->apiResponse($apiResponse, $statusCode, $message, $category_product, null);
    }


    /**
     * Delete product from my products.
     */

    public function deleteProduct($id)
    {
        try {
            $product = Product::findOrFail($id);
            if (is_null($product)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Product not found',
                    'errors' => ['product_id' => 'The product does not exist.'],
                ], 404);
            }
            $user = auth()->user();
            // dd($product->user_id  , $user->id);
            if ($product->user_id  != $user->id) {
                return response()->json([
                    'status' => false,
                    'message' => "You are not authorized for this actions",
                    'errors' => [],
                ], 401);
            }
            $product->delete();

            $apiResponse = 'success';
            $statusCode = 200;
            $message = 'Product deleted successfully!';
            $data = [];
            return $this->apiResponse($apiResponse, $statusCode, $message, $data, null);
        } catch (\Throwable $e) {
            $data = [];
            return $this->apiResponse('error', 500, 'An error occurred while deleting the product.', $data, null);
        }
    }


    /**
     * Adding product in my products.
     */
    public function addProduct(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'description' => 'required|string',
                'category' => 'required',
                'size' => 'required_without:other_size',
                'color' => 'required|string',
                'brand' => 'required',
                'product_condition' => 'required|string',
                'product_market_value' => 'required|numeric',
                'product_link' => 'nullable|url',
                'min_rent_days' => 'required|integer',
                'rent_day' => 'required|integer',
                'rent_week' => 'required|integer',
                'rent_month' => 'required|integer',
                'pickup_location' => 'required',
                'images' => 'required|array',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $user = $request->user();
            // dd($user->vendorBankDetails);
            $is_bankdetail = $user->bankAccount;

            if (is_null($is_bankdetail)) {
                $apiResponse = 'error';
                $statusCode = 422;
                $message = "Please enter bank details before adding products";
                $data = [
                    'user_id' => $user->id,
                    'bankdetail' => $is_bankdetail,
                ];
                return $this->apiResponse($apiResponse, $statusCode, $message, $data, null);
            }

            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'category_id' => $request->category,
                'subcat_id' => $request->sub_cat ?? null,
                'size' => $request->size,
                'color' => $request->color,
                'brand' => $request->brand,
                'product_condition' => $request->product_condition,
                'user_id' => $user->id,
                'status' => $is_bankdetail && isset($request->status) ? '1' : '0',
                'product_market_value' => $request->product_market_value,
                'product_link' => $request->product_link,
                'min_days_rent_item' => $request->min_rent_days,
                'rent_day' => $request->rent_day,
                'rent_week' => $request->rent_week,
                'rent_month' => $request->rent_month,
            ];

            $product = Product::create($data);

            $locationData = json_decode($request->pickup_location, true);
            // dd($locationData);
            ProductLocation::create([
                'product_id' => $product->id,
                'country' => $locationData['country'],
                'state' => $locationData['stateOrProvince'],
                'city' => $locationData['city'],
                'pick_up_location' => $locationData['name'],
                'postcode' => $locationData['postcode'] ?? null,
                'latitude' => $locationData['latitude'],
                'longitude' => $locationData['longitude'],
                'product_complete_location' => $locationData['formatted_address'],
                'raw_address' => $request->pickup_location,
                'manul_pickup_location' => $request->manul_pickup_location ?? 1,
            ]);

            if ($request->has('disable_dates')) {
                $disableDates = json_decode($request->disable_dates, true);
                if ($disableDates && count($disableDates)) {
                    foreach ($disableDates as $dateRange) {
                        if (count($dateRange) == 1) {
                            ProductDisableDate::create([
                                'product_id' => $product->id,
                                'disable_date' => \DateTime::createFromFormat('d/m/Y', $dateRange[0])->format('Y-m-d'),
                            ]);
                        } else if (count($dateRange) == 2) {
                            $start = \DateTime::createFromFormat('d/m/Y', $dateRange[0]);
                            $end = \DateTime::createFromFormat('d/m/Y', $dateRange[1]);

                            while ($start <= $end) {
                                ProductDisableDate::create([
                                    'product_id' => $product->id,
                                    'disable_date' => $start->format('Y-m-d'),
                                ]);
                                $start->modify('+1 day');
                            }
                        }
                    }
                }
            }

            if ($request->hasFile('images') && count($request->images)) {
                foreach ($request->images as $index => $image) {
                    $fileName = $product->id . '_' . time() . '_' . $index . '.' . $image->getClientOriginalExtension();
                    $filePath = $image->storeAs('products/images', $fileName, 'public');

                    ProductImage::create([
                        'product_id' => $product->id,
                        'file_name' => $fileName,
                        'file_path' => $filePath,
                    ]);
                }
            }

            DB::commit();

            $apiResponse = 'success';
            $statusCode = 200;
            $message = 'Product Added successfully!';
            $data = [
                'product_id' => $product->id
            ];
            return $this->apiResponse($apiResponse, $statusCode, $message, $data, null);
        } catch (\Throwable $e) {
            DB::rollback();

            $apiResponse = 'error';
            $statusCode = 500;
            $message = $e->getMessage();
            $data = [];
            Log::error($e->getMessage());
            return $this->apiResponse($apiResponse, $statusCode, $message, $data, null);
        }
    }




    /**
     * Updating product from my products.
     */

    public function updateProduct(Request $request, $id)
    {
        // Log the request data
        // Log::info('Update Product Request Data:', $request->all());

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);

            $user = $request->user();
            if ($product->user_id != $user->id) {
                $response = [
                    'status' => false,
                    'message' => "You are not authorized to perform this action",
                    'errors' => [],
                ];
                // Log::info('Update Product Response:', $response);
                return response()->json($response, 401);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'description' => 'required|string',
                'category' => 'required|integer',
                'size' => 'required|string',
                'color' => 'required|string',
                'brand' => 'required|string',
                'product_condition' => 'required|string',
                'product_market_value' => 'required|numeric',
                'product_link' => 'nullable|url',
                // 'min_rent_days' => 'required|integer',
                'disable_dates' => 'nullable|string',
                'rent_day' => 'required|integer',
                'rent_week' => 'required|integer',
                'rent_month' => 'required|integer',
                'pickup_location' => 'required|string',
                // 'deleted_images' => 'nullable|array',
                // 'deleted_images.*' => 'integer',
                'images' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                $response = ['errors' => $validator->errors()];
                // Log::info('Update Product Response:', $response);
                return response()->json($response, 422);
            }

            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'category_id' => $request->category,
                'subcat_id' => $request->sub_cat ?? null,
                'size' => $request->size,
                'color' => $request->color,
                'brand' => $request->brand,
                'product_condition' => $request->product_condition,
                'product_market_value' => $request->product_market_value,
                'product_link' => $request->product_link,
                'min_days_rent_item' => $request->min_rent_days,
                'rent_day' => $request->rent_day,
                'rent_week' => $request->rent_week,
                'rent_month' => $request->rent_month,
            ];

            $product->update($data);

            $locationData = json_decode($request->pickup_location, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Invalid JSON in pickup_location");
            }
            // dd($locationData);
            ProductLocation::updateOrCreate(
                ['product_id' => $product->id],
                [
                    'country' => $locationData['country'],
                    'state' => $locationData['stateOrProvince'],
                    'city' => $locationData['city'],
                    'pick_up_location' => $locationData['name'],
                    'postcode' => $locationData['postcode'] ?? null,
                    'latitude' => $locationData['latitude'],
                    'longitude' => $locationData['longitude'],
                    'complete_pickup_location' => $locationData['formatted_address'],
                    'raw_address' => $request->pickup_location,
                    'manul_pickup_location' => $request->manul_pickup_location,
                ]
            );

            if ($request->has('disable_dates') && !empty($request->disable_dates)) {
                ProductDisableDate::where('product_id', $product->id)->delete();
                // Log::info('sdfsdfsdfsdffsdfsdfdsfdsfdsfdsfsdfdsfsdfsdfdsfdsfdsfdsfdsfdsfgsdf');

                //   $disableDates = $request->disable_dates;
                $disableDates = json_decode($request->disable_dates, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception("Invalid JSON in disable_dates");
                }

                foreach ($disableDates as $dateRange) {
                    if (count($dateRange) == 1) {
                        ProductDisableDate::create([
                            'product_id' => $product->id,
                            'disable_date' => \DateTime::createFromFormat('Y-m-d', $dateRange[0])->format('Y-m-d'),
                        ]);
                    } else if (count($dateRange) == 2) {
                        $start = \DateTime::createFromFormat('Y-m-d', $dateRange[0]);
                        $end = \DateTime::createFromFormat('Y-m-d', $dateRange[1]);

                        while ($start <= $end) {
                            ProductDisableDate::create([
                                'product_id' => $product->id,
                                'disable_date' => $start->format('Y-m-d'),
                            ]);
                            $start->modify('+1 day');
                        }
                    }
                }
            }

            if ($request->has('deleted_images')) {
                $deletedImages = json_decode($request->deleted_images);
                if (count($deletedImages)) {
                    foreach ($deletedImages as $imageId) {
                        $image = ProductImage::find($imageId);
                        if ($image && $image->product_id == $product->id) {
                            Storage::disk('public')->delete($image->file_path);
                            $image->delete();
                        }
                    }
                }
            }

            if ($request->hasFile('images') && count($request->file('images')) > 0) {
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

            DB::commit();

            $response = [
                'apiResponse' => 'success',
                'statusCode' => 200,
                'message' => 'Product updated successfully!',
                'data' => [
                    'product_id' => $product->id
                ],
            ];
            return $this->apiResponse($response['apiResponse'], $response['statusCode'], $response['message'], $response['data'], null);
        } catch (\Throwable $e) {
            DB::rollback();

            $response = [
                'apiResponse' => 'error',
                'statusCode' => 500,
                'message' => $e->getMessage(),
                'data' => [],
            ];
            return $this->apiResponse($response['apiResponse'], $response['statusCode'], $response['message'], $response['data'], null);
        }
    }




    /**
     * getting required data for product add/edit page.
     */
    public function getFormData()
    {
        $user = auth()->user();
        $is_added = $user->bankAccount;
        if (is_null($is_added)) {
            $added = false;
        } else {
            $added = true;
        }
        try {
            $categories = getParentCategory();
            $brands = getBrands();
            $sizes = getAllsizes();
            $colors = getColors();

            $categoryData = $categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'label' => $category->name,
                    'value' => $category->name,
                    'Subcategory' => getChild($category->id)->map(function ($subCategory) {
                        return [
                            'id' => $subCategory->id,
                            'label' => $subCategory->name,
                            'value' => $subCategory->name
                        ];
                    }),
                ];
            });

            $brandData = $brands->map(function ($brand) {
                return [
                    'id' => $brand->id,
                    'label' => $brand->name,
                    'value' => $brand->name,
                ];
            });

            $sizeData = $sizes->map(function ($size) {
                return [
                    'id' => $size->id,
                    'label' => $size->name,
                    'value' => $size->name,
                ];
            });

            $colorData = $colors->map(function ($color) {
                return [
                    'id' => $color->id,
                    'label' => $color->name,
                    'value' => $color->name,
                ];
            });

            $conditionData = [
                ['id' => '1', 'label' => 'Hardly used', 'value' => 'Hardly used'],
                ['id' => '2', 'label' => 'Great condition', 'value' => 'Great condition'],
                ['id' => '3', 'label' => 'Good condition', 'value' => 'Good condition'],
                ['id' => '4', 'label' => 'Fair condition', 'value' => 'Fair condition'],
            ];
            $rentalDays = [
                ['id' => '1', 'label' => '7 Days', 'value' => '7'],
                ['id' => '2', 'label' => '14 Days', 'value' => '14'],
                ['id' => '3', 'label' => '30 Days', 'value' => '30'],
                // ['id' => '4', 'label' => 'Fair condition', 'value' => 'Fair condition'],
            ];

            $data = [
                'Category' => $categoryData,
                'Brand' => $brandData,
                'Size' => $sizeData,
                'Color' => $colorData,
                'Condition' => $conditionData,
                'rentaldays' => $rentalDays,
                'bank_account' => $added,
            ];

            return response()->json([
                'status' => true,
                'message' => 'Form data fetched successfully!',
                'data' => $data,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => [],
            ], 500);
        }
    }






    private function getProduct($id)
    {
        if ($id) {
            return Product::with(['locations', 'allImages', 'thumbnailImage', 'get_size', 'favorites', 'category', 'disableDates', 'retailer', 'get_color', 'get_brand', 'subcategory',])
                ->whereId($id)
                ->first()
                ->makeVisible(['file_path']);
        }
        return null;
    }


    /**
     * index product listing.
     */


    public function getAllProducts(Request $request)
    {
        try {
            $authUserId = auth()->user()->id;

            $query = Product::where('user_id', '!=', $authUserId);

            if ($request->has('Category')) {
                $categories = explode(',', $request->input('Category'));
                $query->filterByCategories($categories);
            }

            if ($request->has('Brand')) {
                $brands = explode(',', $request->input('Brand'));
                $query->filterByBrands($brands);
            }

            if ($request->has('Size')) {
                $sizes = explode(',', $request->input('Size'));
                $query->filterBySizes($sizes);
            }

            if ($request->has('Color')) {
                $colors = explode(',', $request->input('Color'));
                $query->filterByColors($colors);
            }

            if ($request->has('Price')) {
                $priceRange = explode(',', $request->input('Price'));
                $query->filterByPriceRange($priceRange);
            }

            if ($request->has('Condition')) {
                $conditions = explode(',', $request->input('Condition'));
                $query->filterByCondition($conditions);
            }

            $products = $query->get();

            $categories = getParentCategory();
            $brands = getBrands();
            $sizes = getAllsizes();
            $colors = getColors();

            $filters = [
                [
                    'id' => '1',
                    'name' => 'Category',
                    'subItems' => $categories->map(function ($category) {
                        return [
                            'id' => $category->id,
                            'name' => $category->name,
                            'isSelect' => false,
                        ];
                    })->toArray(),
                ],
                [
                    'id' => '2',
                    'name' => 'Size',
                    'subItems' => $sizes->map(function ($size) {
                        return [
                            'id' => $size->id,
                            'name' => $size->name,
                            'isSelect' => false,
                        ];
                    })->toArray(),
                ],
                [
                    'id' => '3',
                    'name' => 'Color',
                    'subItems' => $colors->map(function ($color) {
                        return [
                            'id' => $color->id,
                            'name' => $color->name,
                            'isSelect' => false,
                        ];
                    })->toArray(),
                ],
                [
                    'id' => '4',
                    'name' => 'Brand',
                    'subItems' => $brands->map(function ($brand) {
                        return [
                            'id' => $brand->id,
                            'name' => $brand->name,
                            'isSelect' => false,
                        ];
                    })->toArray(),
                ],
                [
                    'id' => '5',
                    'name' => 'Price',
                    'subItems' => [
                        ['id' => '1', 'name' => 'below 1000', 'isSelect' => false],
                        ['id' => '2', 'name' => '1000-2000', 'isSelect' => false],
                        ['id' => '3', 'name' => '2000-3000', 'isSelect' => false],
                        ['id' => '4', 'name' => 'above 3000', 'isSelect' => false],
                    ],
                ],
                [
                    'id' => '6',
                    'name' => 'Condition',
                    'subItems' => [
                        ['id' => '1', 'name' => 'Excellent', 'isSelect' => false],
                        ['id' => '2', 'name' => 'Good', 'isSelect' => false],
                        ['id' => '3', 'name' => 'Fair', 'isSelect' => false],
                    ],
                ],
            ];

            // Transform the products
            $transformedProducts = $products->map(function ($product) {
                $allImages = $product->allImages->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'product_id' => $image->product_id,
                        'file_name' => $image->file_name,
                        'file_path' => $image->file_path,
                        'created_at' => $image->created_at,
                        'updated_at' => $image->updated_at,
                    ];
                });

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'specification' => $product->specification,
                    'rentaltype' => $product->rentaltype,
                    'user_id' => $product->user_id,
                    'category_id' => $product->category_id,
                    'subcat_id' => $product->subcat_id,
                    'quantity' => $product->quantity,
                    'rent' => $product->rent,
                    'min_days_rent_item' => $product->min_days_rent_item,
                    'price' => $product->price,
                    'security' => $product->security,
                    'status' => $product->status,
                    'other_size' => $product->other_size,
                    'condition' => $product->condition,
                    'brand' => $product->get_brand->name ?? null,
                    'color' => $product->get_color->name ?? null,
                    'size' => $product->get_size->name ?? null,
                    'rent_day' => $product->rent_day,
                    'rent_week' => $product->rent_week,
                    'rent_month' => $product->rent_month,
                    'modified_by' => $product->modified_by,
                    'modified_user_type' => $product->modified_user_type,
                    'available' => $product->available,
                    'city' => $product->city,
                    'product_market_value' => $product->product_market_value,
                    'product_link' => $product->product_link,
                    'average_rating' => $product->average_rating,
                    'product_image_url' => $product->thumbnailImage->file_path ?? null,
                    'all_images' => $allImages,
                    'favourites' => !is_null($product->favorites) ? true : false,
                ];
            })->toArray();

            return response()->json([
                'status' => true,
                'message' => 'Products fetched successfully!',
                'data' => [
                    'products' => $transformedProducts,
                    'filters' => $filters,
                ],
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => [],
            ], 500);
        }
    }


    /**
     * listing of user product.
     */
    public function getAuthUserProducts()
    {
        try {
            $authUserId = auth()->user()->id;
            $products = Product::where('user_id', $authUserId)->get();

            $transformedProducts = $products->map(function ($product) {
                $allImages = $product->allImages->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'product_id' => $image->product_id,
                        'file_name' => $image->file_name,
                        'file_path' => $image->file_path,
                        'created_at' => $image->created_at,
                        'updated_at' => $image->updated_at
                    ];
                });

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'specification' => $product->specification,
                    'rentaltype' => $product->rentaltype,
                    'user_id' => $product->user_id,
                    'category_id' => $product->category_id,
                    'subcat_id' => $product->subcat_id,
                    'quantity' => $product->quantity,
                    'rent' => $product->rent,
                    'min_days_rent_item' => $product->min_days_rent_item,
                    'price' => $product->price,
                    'security' => $product->security,
                    'status' => $product->status,
                    'other_size' => $product->other_size,
                    'condition' => $product->condition,
                    'brand' => $product->get_brand->name ?? null,
                    'color' => $product->get_color->name ?? null,
                    'size' => $product->get_size->name ?? null,
                    'rent_day' => $product->rent_day,
                    'rent_week' => $product->rent_week,
                    'rent_month' => $product->rent_month,
                    'modified_by' => $product->modified_by,
                    'modified_user_type' => $product->modified_user_type,
                    'available' => $product->available,
                    'city' => $product->city,
                    'product_market_value' => $product->product_market_value,
                    'product_link' => $product->product_link,
                    'average_rating' => $product->average_rating,
                    'product_image_url' => $product->thumbnailImage->file_path ?? null,
                    'all_images' => $allImages,
                    'favourites' => !is_null($product->favorites) ? true : false,
                    'created_at' => $product->created_at,

                ];
            })->toArray();

            return response()->json([
                'status' => true,
                'message' => 'Products fetched successfully!',
                'data' => ['products' => $transformedProducts],
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => [],
            ], 500);
        }
    }




    public function getAllProductsById($id)
    {
        try {
            $product = Product::with('allImages', 'ratings.user')->findOrFail($id);

            if (is_null($product)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Product not found',
                    'data' => [
                        'errors' => ['product_id' => 'The product does not exist.'],
                    ]
                ], 404);
            }

            $user = auth()->user();
            $authUserId = $user->id;

            $queries = Query::where('product_id', $id)->where('user_id', $authUserId)->get();

            $categories = getParentCategory();
            $brands = getBrands();
            $sizes = getAllsizes();
            $colors = getColors();

            $categoryData = $categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'label' => $category->name,
                    'value' => $category->name,
                    'Subcategory' => getChild($category->id)->map(function ($subCategory) {
                        return [
                            'id' => $subCategory->id,
                            'label' => $subCategory->name,
                            'value' => $subCategory->name
                        ];
                    }),
                ];
            });

            $brandData = $brands->map(function ($brand) {
                return [
                    'id' => $brand->id,
                    'label' => $brand->name,
                    'value' => $brand->name,
                ];
            });

            $sizeData = $sizes->map(function ($size) {
                return [
                    'id' => $size->id,
                    'label' => $size->name,
                    'value' => $size->name,
                ];
            });

            $colorData = $colors->map(function ($color) {
                return [
                    'id' => $color->id,
                    'label' => $color->name,
                    'value' => $color->name,
                ];
            });

            $conditionData = [
                ['id' => '1', 'label' => 'Hardly used', 'value' => 'Hardly used'],
                ['id' => '2', 'label' => 'Great condition', 'value' => 'Great condition'],
                ['id' => '3', 'label' => 'Good condition', 'value' => 'Good condition'],
                ['id' => '4', 'label' => 'Fair condition', 'value' => 'Fair condition'],
            ];

            $rentalDays = [
                ['id' => '1', 'label' => '7 Days', 'value' => '7'],
                ['id' => '2', 'label' => '14 Days', 'value' => '14'],
                ['id' => '3', 'label' => '30 Days', 'value' => '30'],
            ];

            $productDetails = $this->getProduct($id);

            // Include subcategory name in product details
            $productDetails->subcategory_name = $productDetails->subcategory ? $productDetails->subcategory->name : null;

            // Count the number of reviews and calculate rating percentage
            $reviewCount = $product->ratings->count();
            $ratingPercentages = [];

            for ($i = 5; $i >= 1; $i--) {
                $ratingCount = $product->ratings->where('rating', $i)->count();
                $percentage = $reviewCount > 0 ? round(($ratingCount / $reviewCount) * 100) : 0;
                $ratingPercentages[] = ['id' => $i, 'per' => "{$percentage}%"];
            }

            // Map the images and reviews
            $productDetails->all_images = $product->allImages->map(function ($image) {
                return [
                    'id' => $image->id,
                    'product_id' => $image->product_id,
                    'file_name' => $image->file_name,
                    'file_path' => storage_path($image->file_path),
                    'created_at' => $image->created_at,
                    'updated_at' => $image->updated_at
                ];
            });

            $productDetails->reviews = $product->ratings->map(function ($review) {
                return [
                    'id' => $review->id,
                    'product_id' => $review->product_id,
                    'order_id' => $review->order_id,
                    'user_id' => $review->user_id,
                    'user_name' => $review->user->name,
                    'user_profile_pic' => $review->user->frontend_profile_url,
                    'rating' => $review->rating,
                    'review' => $review->review,
                    'created_at' => $review->created_at,
                    'updated_at' => $review->updated_at
                ];
            });

            return response()->json([
                'status' => true,
                'message' => 'Product details fetched successfully',
                'data' => [
                    'product' => $productDetails,
                    'categories' => $categoryData,
                    'brands' => $brandData,
                    'sizes' => $sizeData,
                    'colors' => $colorData,
                    'conditions' => $conditionData,
                    'rentaldays' => $rentalDays,
                    'queries' => $queries->map(function ($query) {
                        return [
                            'id' => $query->id,
                            'user_id' => $query->user_id,
                            'product_id' => $query->product_id,
                            'for_user' => $query->for_user,
                            'query_message' => $query->query_message,
                            'status' => $query->status,
                            'date_range' => $query->date_range,
                            'start_date' => $query->start_date,
                            'end_date' => $query->end_date,
                            'negotiate_price' => $query->negotiate_price,
                        ];
                    }),
                    'review_count' => $reviewCount,
                    'rating_percentages' => $ratingPercentages,
                    'loggedInUser' => $user,
                ],
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => [
                    'errors' => []
                ]
            ], 500);
        }
    }






    public function editProduct(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            // $product = Product::findOrFail($id);
            if (is_null($product)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Product not found',
                    'errors' => ['product_id' => 'The product does not exist.'],
                ], 404);
            }

            $productDetails = $this->getProduct($product->id);

            return response()->json([
                'status' => true,
                'message' => 'product details fetched succesfully',
                'data' => $productDetails,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => []
            ], 500);
        }
    }

    public function checkout(Request $request, $id)
    {
        try {
            $query = Query::findOrFail($id);
            $product_id = $query->product_id;
            $productDetails = $this->getProduct($product_id);
            $user_id = $productDetails->user_id;
            $user = User::findOrFail($user_id);
            // dd($user);

            $productDetails->all_images = $productDetails->allImages->map(function ($image) {
                return [
                    'id' => $image->id,
                    'product_id' => $image->product_id,
                    'file_name' => $image->file_name,
                    'file_path' => $image->file_path,
                    'created_at' => $image->created_at,
                    'updated_at' => $image->updated_at,
                ];
            })->toArray();

            $productDetailsArray = [
                'id' => $productDetails->id,
                'name' => $productDetails->name,
                'description' => $productDetails->description,
                'specification' => $productDetails->specification,
                'rentaltype' => $productDetails->rentaltype,
                'user_id' => $productDetails->user_id,
                'category_id' => $productDetails->category_id,
                'subcat_id' => $productDetails->subcat_id,
                'quantity' => $productDetails->quantity,
                'rent' => $productDetails->rent,
                'min_days_rent_item' => $productDetails->min_days_rent_item,
                'price' => $productDetails->price,
                'security' => $productDetails->security,
                'status' => $productDetails->status,
                'other_size' => $productDetails->other_size,
                'condition' => $productDetails->condition,
                'brand' => $productDetails->get_brand->name ?? null,
                'color' => $productDetails->get_color->name ?? null,
                'size' => $productDetails->get_size->name ?? null,
                'rent_day' => $productDetails->rent_day,
                'rent_week' => $productDetails->rent_week,
                'rent_month' => $productDetails->rent_month,
                'modified_by' => $productDetails->modified_by,
                'modified_user_type' => $productDetails->modified_user_type,
                'available' => $productDetails->available,
                'city' => $productDetails->city,
                'product_market_value' => $productDetails->product_market_value,
                'product_link' => $productDetails->product_link,
                'average_rating' => $productDetails->average_rating,
                'product_image_url' => $productDetails->thumbnailImage->file_path ?? null,
                'all_images' => $productDetails->all_images,
                'favourites' => !is_null($productDetails->favorites),


            ];

            $price = $query->negotiate_price ?? $productDetails->getCalculatedPrice($query->date_range);

            return response()->json([
                'status' => true,
                'message' => 'Product details fetched successfully',
                'data' => [
                    'product' => $productDetailsArray,
                    'lender' => $user,
                    'locations' => $productDetails->locations,
                    'queries' => $query,
                    'price' => $price,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch product details',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function ratings(Request $request, $id)
    {
        try {

            $user = auth()->user();
            $validator = Validator::make($request->all(), [
                'rating' => 'required|integer|min:1|max:5',
                'review' => 'nullable|string',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
            }
            $product = Product::find($id);
            $order = Order::where('product_id', $id)->where('user_id', $user->id)->first();
            if (!$product) {
                return response()->json(['status' => false, 'message' => 'Product not found'], 404);
            }

            $rating = ProductRating::updateOrCreate(
                ['user_id' => $user->id, 'product_id' => $id, 'order_id' => $order->id],
                ['rating' => $request->rating, 'review' => $request->review]
            );

            return response()->json(['status' => True, 'message' => 'Rating submitted successfully', 'data' => $rating], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch product details',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function orderDetails(Request $request, $id)
    {
        try {
            $user = auth()->user();
            $query = Query::where('id', $id)->first();
            $product = Product::where('id', $query->product_id)->firstOrFail();
            $order = Order::where('query_id', $query->id)->firstOrFail();
            $productUser = User::where('id', $product->user_id)->firstOrFail();
            $borrower = User::where('id', $order->user_id)->firstOrFail();
            $productDetails = $this->getProduct($order->product_id);

            // Check if the logged-in user has a review on the product
            $userHasReview = $productDetails->ratings->where('user_id', $user->id)->isNotEmpty();

            // Organize images
            $customerPickedUpImages = $order->customerPickedUpImages->map(function ($image) {
                return [
                    'id' => $image->id,
                    'order_id' => $image->order_id,
                    'type' => $image->type,
                    'uploaded_by' => $image->uploaded_by,
                    'file_name' => $image->file,
                    'file_path' => $image->url,
                    'created_at' => $image->created_at,
                    'updated_at' => $image->updated_at
                ];
            });

            $customerReturnedImages = $order->customerReturnedImages->map(function ($image) {
                return [
                    'id' => $image->id,
                    'order_id' => $image->order_id,
                    'type' => $image->type,
                    'uploaded_by' => $image->uploaded_by,
                    'file_name' => $image->file,
                    'file_path' => $image->url,
                    'created_at' => $image->created_at,
                    'updated_at' => $image->updated_at
                ];
            });

            $retailerPickedUpImages = $order->retailerPickedUpImages->map(function ($image) {
                return [
                    'id' => $image->id,
                    'order_id' => $image->order_id,
                    'type' => $image->type,
                    'uploaded_by' => $image->uploaded_by,
                    'file_name' => $image->file,
                    'file_path' => $image->url,
                    'created_at' => $image->created_at,
                    'updated_at' => $image->updated_at
                ];
            });

            $retailerReturnedImages = $order->retailerReturnedImages->map(function ($image) {
                return [
                    'id' => $image->id,
                    'order_id' => $image->order_id,
                    'type' => $image->type,
                    'uploaded_by' => $image->uploaded_by,
                    'file_name' => $image->file,
                    'file_path' => $image->url,
                    'created_at' => $image->created_at,
                    'updated_at' => $image->updated_at
                ];
            });

            return response()->json([
                'status' => true,
                'message' => 'Order details fetched successfully',
                'data' => [
                    'product' => $productDetails,
                    'queries' => $query,
                    'user' => $productUser,
                    'order' => $order,
                    'borrower' => $borrower,
                    'userHasReview' => $userHasReview, // Add the key here
                    'images' => [
                        'customerPickedUpImages' => $customerPickedUpImages,
                        'customerReturnedImages' => $customerReturnedImages,
                        'retailerPickedUpImages' => $retailerPickedUpImages,
                        'retailerReturnedImages' => $retailerReturnedImages,
                    ],
                ],
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => [
                    'errors' => []
                ]
            ], 500);
        }
    }
}
