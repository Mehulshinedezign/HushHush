<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ProductTrait;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\NeighborhoodCity;
use App\Models\Product;
use App\Models\ProductDisableDate;
use App\Models\ProductFavorite;
use App\Models\ProductImage;
use App\Models\ProductLocation;
use App\Models\ProductUnavailability;
use App\Models\Size;
use App\Models\User;
use App\Notifications\ItemYouLike;
use Carbon\CarbonPeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    public function addFavorite(Request $request, $id)
    {
        try {
            // Check if the product exists
            $product = Product::find($id);
            if (is_null($product)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Product not found',
                    'errors' => ['product_id' => 'The product does not exist.'],
                ], 404);
            }

            // Check if the product is already in the user's favorites
            $favorite = ProductFavorite::where('user_id', auth()->user()->id)->where('product_id', $id)->first();
            if (is_null($favorite)) {
                // Add to favorites if not already in the list
                ProductFavorite::create([
                    'product_id' => $id,
                    'user_id' => auth()->user()->id
                ]);
                $message = 'Added to wishlist';
            } else {
                // Remove from favorites if already in the list
                $favorite->delete();
                $message = 'Removed from wishlist';
            }

            // Get product details
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


    public function addProduct(Request $request)
    {
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
                //'disable_dates' => 'required|string',
                'state' => 'required|integer',
                'city' => 'required|integer',
                'rent_day' => 'required|integer',
                'rent_week' => 'required|integer',
                'rent_month' => 'required|integer',
                'pickup_location' => 'required|string',
                'images' => 'required|array',
                //'images.*' => 'string'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $user = $request->user();
            $is_bankdetail = $user->vendorBankDetails;

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
                'state' => $request->state,
                'city' => $request->city,
                'rent_day' => $request->rent_day,
                'rent_week' => $request->rent_week,
                'rent_month' => $request->rent_month,
            ];

            $product = Product::create($data);

            if ($request->cookie('img_token')) {
                ProductImage::where('file_path', $request->cookie('img_token'))
                    ->update(['product_id' => $product->id, 'image_token' => null]);
            }

            ProductLocation::create([
                'product_id' => $product->id,
                'map_address' => $request->pickup_location,
            ]);

            // Handle disable dates
            if ($request->has('disable_dates')) {
                $disableDates = json_decode($request->disable_dates, true);
                foreach ($disableDates as $dateRange) {
                    if (count($dateRange) == 1) {
                        // Single date
                        ProductDisableDate::create([
                            'product_id' => $product->id,
                            'disable_date' => \DateTime::createFromFormat('d/m/Y', $dateRange[0])->format('Y-m-d'),
                        ]);
                    } else if (count($dateRange) == 2) {
                        // Date range
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

            // // Handle images
            if ($request->hasFile('images')) {
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




    public function updateProduct(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $user = $request->user();
            if ($product->user_id != $user->id) {
                return response()->json([
                    'status' => false,
                    'message' => "You are not authorized to perform this action",
                    'errors' => [],
                ], 401);
            }

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
                // 'disable_dates' => 'array',
                'disable_dates.*' => 'string',
                'state' => 'required|integer',
                'city' => 'required|integer',
                'rent_day' => 'required|integer',
                'rent_week' => 'required|integer',
                'rent_month' => 'required|integer',
                'pickup_location' => 'required|string',
                // 'images' => 'array',
                // 'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
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
                'state' => $request->state,
                'city' => $request->city,
                'rent_day' => $request->rent_day,
                'rent_week' => $request->rent_week,
                'rent_month' => $request->rent_month,
            ];

            $product->update($data);

            ProductLocation::where('product_id', $product->id)->update([
                'map_address' => $request->pickup_location,
            ]);

            if ($request->has('disable_dates')) {
                ProductDisableDate::where('product_id', $product->id)->delete();
                $disableDates = json_decode($request->disable_dates, true);
                foreach ($disableDates as $dateRange) {
                    if (count($dateRange) == 1) {
                        // Single date
                        ProductDisableDate::create([
                            'product_id' => $product->id,
                            'disable_date' => \DateTime::createFromFormat('d/m/Y', $dateRange[0])->format('Y-m-d'),
                        ]);
                    } else if (count($dateRange) == 2) {
                        // Date range
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

            $apiResponse = 'success';
            $statusCode = 200;
            $message = 'Product updated successfully!';
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



    public function getFormData()
    {
        // dd('here');
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

            $data = [
                'Category' => $categoryData,
                'Brand' => $brandData,
                'Size' => $sizeData,
                'Color' => $colorData,
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
            return Product::with(['locations', 'allImages', 'thumbnailImage', 'get_size', 'favorites', 'category', 'disableDates', 'retailer'])
                ->whereId($id)
                ->first()
                ->makeVisible(['file_path']);
        }
        return null;
    }


    public function getAllProducts()
    {
        try {
            $authUserId = auth()->user()->id;
            $products = Product::where('user_id', '!=', $authUserId)->get();

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
            $product = Product::with('allImages',)->findOrFail($id);
            if (is_null($product)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Product not found',
                    'errors' => ['product_id' => 'The product does not exist.'],
                ], 404);
            }

            // dd($image->file_path);
            $productDetails = $this->getProduct($id);
            $productDetails->all_images = $productDetails->allImages->map(function ($image) {
                return [
                    'id' => $image->id,
                    'product_id' => $image->product_id,
                    'file_name' => $image->file_name,
                    'file_path' => storage_path($image->file_path),
                    'created_at' => $image->created_at,
                    'updated_at' => $image->updated_at
                ];
            });

            return response()->json([
                'status' => true,
                'message' => 'Product details fetched successfully',
                'data' => $productDetails,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => []
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
}
