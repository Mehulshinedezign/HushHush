<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ProductTrait;
use App\Models\Category;
use App\Models\NeighborhoodCity;
use App\Models\Product;
use App\Models\ProductDisableDate;
use App\Models\ProductFavorite;
use App\Models\ProductImage;
use App\Models\ProductLocation;
use App\Models\ProductUnavailability;
use App\Models\User;
use App\Notifications\ItemYouLike;
use Carbon\CarbonPeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            $categories = Category::where('status', true)->get();

            $products = $this->getProducts($request);
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
        // dd("wishlist HERE");

        try {
            $productFavorites = ProductFavorite::with('product.thumbnailImage', 'product.category')->where('user_id', auth()->user()->id)->orderByDesc('id')->paginate($request->global_product_pagination);

            $wishlistProducts = $productFavorites->map(function ($productFavorit) {
                return [
                    'id' => $productFavorit->product['id'],
                    'name' => $productFavorit->product['name'],
                    'description' => $productFavorit->product['description'],
                    'specification' => $productFavorit->product['specification'],
                    'rentaltype' => $productFavorit->product['rentaltype'],
                    'user_id' => $productFavorit->product['user_id'],
                    'category_id' => $productFavorit->product['category_id'],
                    'subcat_id' => $productFavorit->product['subcat_id'],
                    'quantity' => $productFavorit->product['quantity'],
                    'rent' => $productFavorit->product['rent'],
                    'min_days_rent_item' => $productFavorit->product['min_days_rent_item'],
                    'price' => $productFavorit->product['price'],
                    'security' => $productFavorit->product['security'],
                    'status' => $productFavorit->product['status'],
                    'other_size' => $productFavorit->product['other_size'],
                    'condition' => $productFavorit->product['condition'],
                    'brand' => $productFavorit->product['brand'],
                    'color' => $productFavorit->product['color'],
                    'size' => $productFavorit->product['size'],
                    'modified_by' => $productFavorit->product['modified_by'],
                    'modified_user_type' => $productFavorit->product['modified_user_type'],
                    'available' => $productFavorit->product['available'],
                    'city' => $productFavorit->product['city'],
                    'neighborhood_city' => $productFavorit->product['neighborhood_city'],
                    'product_market_value' => $productFavorit->product['product_market_value'],
                    'product_link' => $productFavorit->product['product_link'],
                    'created_at' => $productFavorit->product['created_at'],
                    'updated_at' => $productFavorit->product['updated_at'],
                    'deleted_at' => $productFavorit->product['deleted_at'],
                    'average_rating' => $productFavorit->product['average_rating'],

                ];
            })->toArray();

            $apiResponse = 'success';
            $statusCode = '200';
            $message = 'Wishlist product fetched successfully!';

            if ($wishlistProducts) {
                return $this->apiResponse($apiResponse, $statusCode, $message, $wishlistProducts, null);
            } else {
                $data = [];
                return $this->apiResponse($apiResponse, $statusCode, 'There is no product list add', $data, null);
            }
        } catch (\Throwable $e) {
            $apiResponse = 'error';
            $statusCode = '500';
            $message = $e->getMessage();
            return $this->apiResponse($apiResponse, $statusCode, $message, null, null);
        }
    }

    /**
     * Add the product in wish list
     */
    public function addFavorite(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'productid' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $product = ProductFavorite::where('user_id', auth()->user()->id)->where('product_id', $request->productid)->first();
            if (is_null($product)) {
                ProductFavorite::insert([
                    'product_id' => $request->productid,
                    'user_id' => auth()->user()->id
                ]);
            }

            $apiResponse = 'success';
            $statusCode = 200;
            $message = 'Added to wishlist';
        } catch (\Throwable $e) {
            $apiResponse = 'error';
            $statusCode = 500;
            $message = 'Failed to add to wishlist. Error: ' . $e->getMessage();
        }

        return $this->apiResponse($apiResponse, $statusCode, $message);
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

    // public function addProduct(Request $request)
    // {

    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'name' => 'required|string',
    //             'description' => 'required|string',
    //             'rentaltype' => 'required|string',
    //             'category' => 'required',
    //             'size' => 'required_without:other_size',
    //             'other_size' => 'required_without:size',
    //             'color' => 'required|string',
    //             'brand' => 'required',
    //             'product_condition' => 'required|string',
    //             'rent' => 'required|numeric',
    //             'price' => 'required|numeric',
    //             'product_market_value' => 'required|numeric',
    //             'product_link' => 'nullable|url',
    //             'min_rent_days' => 'required|integer',
    //             'neighborhood' => 'required|',
    //             'neighborhoodcity' => 'required|string',
    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json(['errors' => $validator->errors()], 422);
    //         }

    //         $user = $request->user();
    //         $is_bankdetail = $user->vendorBankDetails;

    //         // dd("AVAIL",$is_bankdetail);
    //         $data = [
    //             'name' => $request->name,
    //             'description' => $request->description,
    //             'rentaltype' => $request->rentaltype,
    //             'category_id' => $request->category,
    //             'subcat_id' => $request->sub_cat ?? null,
    //             'size' => $request->size,
    //             'other_size' => $request->other_size,
    //             'color' => $request->color,
    //             'brand' => $request->brand,
    //             'condition' => $request->product_condition,
    //             'user_id' => $user->id,
    //             'rent' => $request->rent,
    //             'price' => $request->price,
    //             // 'status' => $is_bankdetail ? isset($request->status) ? '1' : '0' : '0',
    //             'status' => $is_bankdetail && isset($request->status) ? '1' : '0',

    //             'product_market_value' => $request->product_market_value,
    //             'product_link' => $request->product_link,
    //             'min_days_rent_item' => $request->min_rent_days,
    //         ];

    //         if ($request->neighborhoodcity && $request->neighborhood) {
    //             $data['city'] = $request->neighborhoodcity;
    //             $data['neighborhood_city'] = $request->neighborhood;
    //         }

    //         $product = Product::create($data);


    //         if ($request->cookie('img_token')) {
    //             ProductImage::where('image_token', $request->cookie('img_token'))
    //                 ->update(['product_id' => $product->id, 'image_token' => null]);
    //         }

    //         $neighborhoodcity = NeighborhoodCity::findOrFail($request->neighborhood);
    //         ProductLocation::create([
    //             'product_id' => $product->id,
    //             'map_address' => $neighborhoodcity->name,
    //         ]);


    //         $apiResponse = 'success';
    //         $statusCode = 200;
    //         $message = 'Product Added successfully!';
    //         $data = [];
    //         return $this->apiResponse($apiResponse, $statusCode, $message, $data, null);
    //     } catch (\Throwable $e) {
    //         // dd($e->getMessage());
    //         $apiResponse = 'error';
    //         $statusCode = 500;
    //         $message = $e->getMessage();
    //         $data = [];
    //         return $this->apiResponse($apiResponse, $statusCode, $message, $data, null);
    //     }
    // }

    // public function updateProduct(Request $request, $id)
    // {
    //     try {
    //         $product = Product::findOrFail($id);

    //         $validator = Validator::make($request->all(), [
    //             'name' => 'required|string',
    //             'description' => 'required|string',
    //             'rentaltype' => 'required|string',
    //             'category' => 'required',
    //             'size' => 'required_without:other_size',
    //             'other_size' => 'required_without:size',
    //             'color' => 'required|string',
    //             'brand' => 'required',
    //             'product_condition' => 'required|string',
    //             'rent' => 'required|numeric',
    //             'price' => 'required|numeric',
    //             'product_market_value' => 'required|numeric',
    //             'product_link' => 'nullable|url',
    //             'min_rent_days' => 'required|integer',
    //             'neighborhood' => 'required',
    //             'neighborhoodcity' => 'required',
    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json(['errors' => $validator->errors()], 422);
    //         }

    //         $product->update($request->all());

    //         if ($request->has('neighborhood') && $request->has('neighborhoodcity')) {
    //             $product->city = $request->neighborhoodcity;
    //             $product->neighborhood_city = $request->neighborhood;
    //             $product->save();

    //             $neighborhoodcity = NeighborhoodCity::findOrFail($request->neighborhood);
    //             $product->productLocation()->update([
    //                 'map_address' => $neighborhoodcity->name,
    //             ]);
    //         }

    //         $apiResponse = 'success';
    //         $statusCode = 200;
    //         $message = 'Product Updated successfully!';
    //         return $this->apiResponse($apiResponse, $statusCode, $message, null, null);
    //     } catch (\Throwable $e) {
    //         return $this->apiResponse('error', 500, 'An error occurred while updating the product.', null, null);
    //     }
    // }

    public function deleteProduct($id)
    {
        try {
            $product = Product::findOrFail($id);
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

    // public function view(Request $request)
    // {

    //     try {
    //         // dd($request->all());
    //         // dd('1');
    //         $product = $this->getProduct($request, $request->id);
    //         dd($product);
    //         if (is_null($product)) {
    //             return redirect()->back()->with('message', __('product.messages.notAvailable'));
    //         }
    //         dd(2);

    //         $apiResponse = 'success';
    //         $statusCode = 200;
    //         $message = 'Product fetched successfully!';

    //         $additionalDescriptions = [
    //             'This product is simply amazing!',
    //             'You wont believe the quality of this item',
    //             'A must-have for any collection',
    //             'Perfect for everyday use or special occasions.',
    //             'Highly recommended by satisfied customers.',
    //             'Exceptional value for the price.',
    //             'One of our most popular items.',
    //             'You will wonder how you ever lived without it.',
    //             'Combines style and functionality beautifully.',
    //             'Guaranteed to exceed your expectations.',
    //         ];

    //         dd("here", $product);
    //         $ratings = $product->ratings->map(function ($rating) {
    //             return [
    //                 'user_id' => $rating->user_id,
    //                 'review' => $rating->review,
    //                 'user_name' => @$rating->user->name,
    //                 "review_user_profile" => asset('storage/profiles/' . $rating->user->profile_file),
    //                 'rating' => $rating->rating,
    //                 'review_date' => date('Y-m-d', strtotime($rating->created_at)),
    //             ];
    //         })->toARray();

    //         dd("here : ", $product->retailer->profile_file);
    //         $productDetails = [
    //             'id' => $product->id,
    //             'name' => $product->name,
    //             'product_image_url' => [$product->thumbnailImage && filter_var($product->thumbnailImage->url, FILTER_VALIDATE_URL) ? $product->thumbnailImage->url : null],
    //             'description' => $product->description,
    //             'addition_description' => $additionalDescriptions[array_rand($additionalDescriptions)],
    //             'category_name' => $product->category->name,
    //             'rent' => $product->rent,
    //             'size' => $product->size,
    //             'pickup_location' => $product->locations[0]->map_address ?? null,
    //             'disabled_date' => [date('Y-m-d', mt_rand(strtotime('1 year'), strtotime('+1 year')))],
    //             'lender_info' => [
    //                 'user_id' => $product->retailer->id,
    //                 'user_name' => $product->retailer->username,
    //                 'name' => $product->retailer->name,
    //                 'lender_profile' => asset('storage/profiles/' . $product->retailer->profile_file),
    //             ],
    //             'review' => $ratings,
    //         ];


    //         return $this->apiResponse($apiResponse, $statusCode, $message, $productDetails, null);
    //     } catch (\Throwable $e) {

    //         return response()->json([
    //             'status' => false,
    //             'message' => $e->getMessage(),
    //             'errors' => []
    //         ], 500);
    //     }
    // }


    // public function addDisableDates(Request $request, $productId)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'dates' => 'required|array',
    //         'dates.*' => 'date_format:Y-m-d',
    //         'start_date' => 'date_format:Y-m-d',
    //         'end_date' => 'date_format:Y-m-d|after_or_equal:start_date',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
    //     }

    //     try {
    //         $disableDates = [];

    //         if ($request->has('start_date') && $request->has('end_date')) {
    //             $period = CarbonPeriod::create($request->start_date, $request->end_date);

    //             foreach ($period as $date) {
    //                 $disableDates[] = [
    //                     'product_id' => $productId,
    //                     'disable_date' => $date->format('Y-m-d'),
    //                     'created_at' => now(),
    //                     'updated_at' => now(),
    //                 ];
    //             }
    //         }

    //         if ($request->has('dates')) {
    //             foreach ($request->dates as $date) {
    //                 $disableDates[] = [
    //                     'product_id' => $productId,
    //                     'disable_date' => $date,
    //                     'created_at' => now(),
    //                     'updated_at' => now(),
    //                 ];
    //             }
    //         }

    //         ProductDisableDate::insert($disableDates);

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Disable dates added successfully!',
    //             'data' => $disableDates,
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => $e->getMessage(),
    //             'errors' => [],
    //         ], 500);
    //     }
    // }

    public function addProduct(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'description' => 'required|string',
                'rentaltype' => 'required|string',
                'category' => 'required',
                'size' => 'required_without:other_size',
                'other_size' => 'required_without:size',
                'color' => 'required|string',
                'brand' => 'required',
                'product_condition' => 'required|string',
                'rent' => 'required|numeric',
                'price' => 'required|numeric',
                'product_market_value' => 'required|numeric',
                'product_link' => 'nullable|url',
                'min_rent_days' => 'required|integer',
                'neighborhood' => 'required',
                'neighborhoodcity' => 'required|string',
                'disable_dates' => 'array',
                'disable_dates.*' => 'string',
            ]);

            // dd($request->all());
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $user = $request->user();
            $is_bankdetail = $user->vendorBankDetails;

            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'rentaltype' => $request->rentaltype,
                'category_id' => $request->category,
                'subcat_id' => $request->sub_cat ?? null,
                'size' => $request->size,
                'other_size' => $request->other_size,
                'color' => $request->color,
                'brand' => $request->brand,
                'condition' => $request->product_condition,
                'user_id' => $user->id,
                'rent' => $request->rent,
                'price' => $request->price,
                'status' => $is_bankdetail && isset($request->status) ? '1' : '0',
                'product_market_value' => $request->product_market_value,
                'product_link' => $request->product_link,
                'min_days_rent_item' => $request->min_rent_days,
            ];

            if ($request->neighborhoodcity && $request->neighborhood) {
                $data['city'] = $request->neighborhoodcity;
                $data['neighborhood_city'] = $request->neighborhood;
            }

            $product = Product::create($data);

            if ($request->cookie('img_token')) {
                ProductImage::where('image_token', $request->cookie('img_token'))
                    ->update(['product_id' => $product->id, 'image_token' => null]);
            }

            $neighborhoodcity = NeighborhoodCity::findOrFail($request->neighborhood);
            ProductLocation::create([
                'product_id' => $product->id,
                'map_address' => $neighborhoodcity->name,
            ]);

            // Handle disable dates
            if ($request->has('disable_dates')) {
                foreach ($request->disable_dates as $dateRange) {

                    $dates = explode(',', $dateRange);
                    if (count($dates) == 1) {
                        // Single date
                        ProductDisableDate::create([
                            'product_id' => $product->id,
                            'disable_date' => $dates[0],
                        ]);
                    } else if (count($dates) == 2) {
                        // Date range
                        $start = new \DateTime($dates[0]);
                        $end = new \DateTime($dates[1]);

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

            $apiResponse = 'success';
            $statusCode = 200;
            $message = 'Product Added successfully!';
            $data = [
                'product_id' => $product->id
            ];
            return $this->apiResponse($apiResponse, $statusCode, $message, $data, null);
        } catch (\Throwable $e) {
            $apiResponse = 'error';
            $statusCode = 500;
            $message = $e->getMessage();
            $data = [];
            return $this->apiResponse($apiResponse, $statusCode, $message, $data, null);
        }
    }


    public function view(Request $request, $id)
    {
        try {
            $product = Product::with(['category', 'thumbnailImage', 'locations', 'retailer'])
                ->findOrFail($id);

            $additionalDescriptions = [
                'This product is simply amazing!',
                'You won\'t believe the quality of this item.',
                'A must-have for any collection.',
                'Perfect for everyday use or special occasions.',
                'Highly recommended by satisfied customers.',
                'Exceptional value for the price.',
                'One of our most popular items.',
                'You will wonder how you ever lived without it.',
                'Combines style and functionality beautifully.',
                'Guaranteed to exceed your expectations.',
            ];

            $disableDates = $product->disableDates->pluck('disable_date')->toArray();

            $productDetails = [
                'id' => $product->id,
                'name' => $product->name,
                'product_image_url' => $product->thumbnailImage ? asset('storage/' . $product->thumbnailImage->url) : null,
                'description' => $product->description,
                'additional_description' => $additionalDescriptions[array_rand($additionalDescriptions)],
                'category_name' => $product->category->name,
                'rent' => $product->rent,
                'size' => $product->size,
                'pickup_location' => $product->locations[0]->map_address ?? null,
                'disabled_dates' => $disableDates,
                'lender_info' => [
                    'user_id' => $product->retailer->id,
                    'user_name' => $product->retailer->username,
                    'name' => $product->retailer->name,
                    'lender_profile' => $product->retailer->profile_file ? asset('storage/profiles/' . $product->retailer->profile_file) : null,
                ],
            ];

            return response()->json([
                'status' => true,
                'message' => 'Product fetched successfully!',
                'data' => $productDetails,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => [],
            ], 500);
        }
    }







    public function updateProduct(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'description' => 'required|string',
                'rentaltype' => 'required|string',
                'category' => 'required',
                'size' => 'required_without:other_size',
                'other_size' => 'required_without:size',
                'color' => 'required|string',
                'brand' => 'required',
                'product_condition' => 'required|string',
                'rent' => 'required|numeric',
                'price' => 'required|numeric',
                'product_market_value' => 'required|numeric',
                'product_link' => 'nullable|url',
                'min_rent_days' => 'required|integer',
                'neighborhood' => 'required',
                'neighborhoodcity' => 'required',
                'disable_dates' => 'array',
                'disable_dates.*' => 'string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $product->update($request->all());

            if ($request->has('neighborhood') && $request->has('neighborhoodcity')) {
                $product->city = $request->neighborhoodcity;
                $product->neighborhood_city = $request->neighborhood;
                $product->save();

                $neighborhoodcity = NeighborhoodCity::findOrFail($request->neighborhood);
                $product->locations()->update([
                    'map_address' => $neighborhoodcity->name,
                ]);
            }

            // Handle disable dates
            if ($request->has('disable_dates')) {
                ProductDisableDate::where('product_id', $product->id)->delete();

                foreach ($request->disable_dates as $dateRange) {
                    $dates = explode(',', $dateRange);
                    if (count($dates) == 1) {
                        // Single date
                        ProductDisableDate::create([
                            'product_id' => $product->id,
                            'disable_date' => $dates[0],
                        ]);
                    } else if (count($dates) == 2) {
                        // Date range
                        $start = new \DateTime($dates[0]);
                        $end = new \DateTime($dates[1]);

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

            $apiResponse = 'success';
            $statusCode = 200;
            $message = 'Product Updated successfully!';
            $data = ['product_id' => $product->id];
            return $this->apiResponse($apiResponse, $statusCode, $message, $data, null);
        } catch (\Throwable $e) {
            dd($e->getMessage());
            $data = [];
            return $this->apiResponse('error', 500, 'An error occurred while updating the product.',  $data, null);
        }
    }

}
