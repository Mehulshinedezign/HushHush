<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Notifications\ItemYouLike;
use Illuminate\Http\Request;
use App\Http\Requests\Customer\FavoriteRequest;
use App\Http\Traits\ProductTrait;
use App\Models\{User, Product, ProductFavorite, Category, NeighborhoodCity, Order, Query};
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    use ProductTrait;

    private $selectedLocation = '';

    public function __construct(Request $request)
    {
        if (!is_null($request->location) && !is_null($request->selected_location)) {
            $this->selectedLocation = $request->selected_location;
        }

        if (!is_null($request->location) && is_null($request->selected_location)) {
            $this->selectedLocation = $request->location;
        }
    }

    /**
     * List products
     *
     * @return Collection
     */


    public function index(Request $request)
    {
        $categories = Category::where('status', 'Active')->get();
        $selectedCategories = $request->input('category', []);
        $selectedSubcategories = $request->input('Subcategory', []);
        $selectedcolor = $request->input('filtercolor', []);
        $selectedcondition = $request->input('condition', []);
        $selectedbrands = $request->input('brand', []);
        $selectedsize = $request->input('size', []);
        $searchKeyword = $request->input('search', '');
        $disabledate = $request->input('filter_date');

        $startDate = null;
        $endDate = null;

        if (!empty($disabledate) && strpos($disabledate, ' - ') !== false) {
            [$startDate, $endDate] = explode(' - ', $disabledate);
        }

        if (auth()->check()) {
            $authUserId = auth()->user()->id;
            $query = Product::with('disableDates', 'ratings')
                ->where('user_id', '!=', $authUserId)
                ->where('status', '1');
        } else {
            $query = Product::with('disableDates', 'ratings')
                ->where('status', '1');
        }

        if (!empty($searchKeyword)) {
            $query->where('name', 'LIKE', '%' . $searchKeyword . '%');
        }

        $query->applyFilters();

        if ($startDate && $endDate) {
            $query->filterByDateRange($startDate, $endDate);
        }
        if ($request->rating) {

            $products = $query->paginate(5);
        } else {
            $products = $query->sort()->paginate(20);
        }

        if ($request->ajax()) {
            $products_count = $products->count();
            $view = view('partials.product-cards', compact('products'))->render();
            return response()->json([
                'status' => true,
                'message' => 'Products fetched successfully!',
                'data' => [
                    'html' => $view,
                    'products_count' => $products_count,
                ],
            ], 200);
        }

        return view('index', compact('products', 'categories'))->with([
            'selectedLocation' => $this->selectedLocation,
            'selectedCategories' => $selectedCategories,
            'selectedSubcategories' => $selectedSubcategories,
            'selectedcolor' => $selectedcolor,
            'selectedcondition' => $selectedcondition,
            'selectedbrands' => $selectedbrands,
            'selectedsize' => $selectedsize,
        ]);
    }







    /**
     * Display a product detail
     *
     * @return \Illuminate\Http\Response
     */


    public function view(Request $request, $id)
    {
        $id = jsdecode_userdata($id);
        $product = Product::findOrFail($id);

        if (is_null($product)) {
            return redirect()->back()->with('message', __('product.messages.notAvailable'));
        }

        $rating_progress = $this->getratingprogress($product);

        $relatedProducts = Product::with('thumbnailImage', 'ratings', 'favorites')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->whereHas('category', function ($q) {
                $q->where('status', '1');
            })
            ->inRandomOrder()
            ->limit(5)
            ->get();

        $disable_dates = $product->disableDates->pluck('disable_date')->toArray();

        $product_buffer = $product->created_at->format('Y-m-d');
        $carbonDate = Carbon::createFromFormat('Y-m-d', $product_buffer);
        $array1 = [];
        array_push($array1, $product_buffer);
        for ($i = 0; $i < 3; $i++) {
            $newDate = $carbonDate->addDay();
            array_push($array1, $newDate->format('Y-m-d'));
        }

        $disable_dates = array_merge($array1, $disable_dates);

        $querydates = [];
        if (auth()->check()) {  // Only execute if the user is authenticated
            $querydates = Query::where(['product_id' => $id, 'user_id' => auth()->user()->id])
                ->select('date_range')
                ->get();

            $userDisableDates = [];
            foreach ($querydates as $query) {
                [$startDate, $endDate] = explode(' - ', $query->date_range);
                $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
                foreach ($period as $date) {
                    $userDisableDates[] = $date->format('Y-m-d');
                }
            }

            $disable_dates = array_merge($disable_dates, $userDisableDates);
        }

        $disable_dates = array_unique($disable_dates);
        sort($disable_dates);

        $productImages = $product->allImages;

        return view('product-detail', compact('product', 'productImages', 'querydates', 'relatedProducts', 'rating_progress', 'disable_dates'));
    }





    /**
     * Display a product retailer
     *
     * @return \Illuminate\Http\Response
     */
    public function retailer(Request $request, $id)
    {
        $retailer = User::whereId(jsdecode_userdata($id))->first();
        $products = Product::with('ratings', 'thumbnailImage')->where('user_id', $retailer->id)->paginate($request->global_pagination);
        $ratedProducts = $products->where('average_rating', '>', '0');
        $averageRating = 0.0;
        if (count($ratedProducts)) {
            $averageRating = $ratedProducts->sum('average_rating') / count($ratedProducts);
        }

        return view('retailer-detail', compact('retailer', 'products', 'averageRating'));
    }

    /**
     * Add product to wishlist
     *
     * @return \Illuminate\Http\Response
     */
    public function addFavorite(FavoriteRequest $request)
    {
        $product = ProductFavorite::where('user_id', auth()->user()->id)->where('product_id', $request->productid)->first();

        $action = 'added';
        if (!is_null($product)) {
            $product->delete();
            $message = __('favorite.messages.deleteProduct');
            $action = 'removed';

            return response()->json(['title' => __('favorite.success'), 'message' => $message, 'action' => $action]);
        }

        ProductFavorite::insert(['product_id' => $request->productid, 'user_id' => auth()->user()->id]);

        return response()->json(['title' => __('favorite.success'), 'message' => __('favorite.messages.addProduct'), 'action' => $action]);
    }

    /**
     * show wishlist products
     *
     * @return \Illuminate\Http\Response
     */


    public function wishlist(Request $request)
    {
        // $products = ProductFavorite::with('product.thumbnailImage', 'product.category')->where('user_id', auth()->user()->id)->orderByDesc('id')->paginate($request->global_product_pagination);
        $products = ProductFavorite::with('product.thumbnailImage', 'product.category')
            ->where('user_id', auth()->user()->id)
            ->orderByDesc('id')->get();

        $user = User::with('notification')->where('id', auth()->user()->id)->first();
        if (!$products->isEmpty() && @$user->notification->item_we_think_you_might_like == "on") {
            $user->notify(new ItemYouLike($user, $products));
        }
        // $product = $products->toArray();

        // dd($products);
        return view('customer.wishlist', compact('products'));
    }
    /**
     * Book the product
     *
     * @return \Illuminate\Http\Response
     */
    public function book(BookRequest $request, $id)
    {
        $message = "";
        $product = $this->checkProductAvailability($request, $id);

        if (!is_null($product)) {
            $validateReserveDate = $this->validateReservationDate($request, $product);
            if (!is_array($validateReserveDate)) {
                $message = __('product.messages.invalidReservationDate');
            }
        } else {
            $message = __('product.messages.notAvailable');
        }
        $location = $this->productNearestLocation($request->latitude, $request->longitude, $id);
        if (is_null($location)) {
            $message = __('product.messages.notAvailableArea');
        }

        if (!empty($message)) {
            return redirect()->back()->with('error', $message);
        }
        $rentalDays = $validateReserveDate['rental_days'];
        $security = $this->getSecurityAmount($product);
        $insurance = $this->getInsuranceAmount($product);
        $transactionFee = $this->getTransactionAmount($product, $rentalDays);
        $total = $request->option == 'insurance' ? $insurance : $security;
        $total += $transactionFee + ($rentalDays * $product->rent);
        $total = number_format((float) $total, 2, '.', '');

        if ($total >= $this->maxTotalAmount) {
            return redirect()->back()->with('message', 'Booking amount should not be greater than $' . $this->maxTotalAmount);
        }

        return view('customer.checkout', compact('product', 'rentalDays', 'location', 'transactionFee', 'security', 'insurance', 'total'))->with(['selectedLocation' => $this->selectedLocation]);
    }

    public function get_transfee(Request $request)
    {
        $transactionFee = getTransactionAmount($request->rentPrice, $request->days);
        return response()->json(['status' => true, 'message' => 'Transaction Fee', 'transactionFee' => $transactionFee]);
    }

    public function openModel(Request $request)
    {

        $openModal = "true";

        $categories = Category::where('status', 'Active')->get();
        $selectedCategories = (isset($request->category)) ? $request->category : [];
        $selectedcolor = (isset($request->filtercolor)) ? $request->filtercolor : [];
        $selectedcondition = (isset($request->condition)) ? $request->condition : [];
        $selectedbrands = (isset($request->brand)) ? $request->brand : [];
        $selectedsize = (isset($request->size)) ? $request->size : [];
        $rentalType = $request->rental_type ?? '';
        $products = $this->getProducts($request);
        $city = (isset($request->neighborhoodcity)) ? $request->neighborhoodcity : [];

        $allProducts = $products->get();
        $products = $products->paginate($request->global_product_pagination);
        $maxPrice = number_format((float)ceil(Product::max('rent')), 2, '.', '');
        $minPrice = number_format((float)floor(Product::min('rent')), 2, '.', '');
        $fourStar = $allProducts->whereBetween('average_rating', [3.1, 5])->count();
        $threeStar = $allProducts->whereBetween('average_rating', [2.1, 3])->count();
        $twoStar = $allProducts->whereBetween('average_rating', [1.1, 2])->count();
        $oneStar = $allProducts->whereBetween('average_rating', [0.1, 1])->count();
        $filters = [
            'maxprice' => $maxPrice,
            'minprice' => $minPrice,
            'categories' => $selectedCategories,
            'selectedcolor' => $selectedcolor,
            'selectedcondition' => $selectedcondition,
            'selectedbrands' => $selectedbrands,
            'selectedsize' => $selectedsize,
            'rental_type' => [$rentalType],
            'rent' => $request->rent ?? $maxPrice,
            'star1' => $oneStar,
            'star2' => $twoStar,
            'star3' => $threeStar,
            'star4' => $fourStar,
            'neighborhoodcity' => $city,

        ];
        return view('index', compact('products', 'categories', 'filters', 'openModal'))->with(['selectedLocation' => $this->selectedLocation]);
    }

    /**
     * Add Product
     */

    public function lenderInfo(Request $request, $id)
    {


        $retailer = User::whereId(jsdecode_userdata($id))->first();
        // dd($retailer);
        $products = Product::with('ratings', 'thumbnailImage')->where('user_id', $retailer->id)->paginate($request->global_pagination);
        $ratedProducts = $products->where('average_rating', '>', '0');
        // dd($ratedProducts);
        $averageRating = 0.0;
        if (count($ratedProducts)) {
            $averageRating = $ratedProducts->sum('average_rating') / count($ratedProducts);
        }

        return view('customer.profile', compact('products', 'retailer'));
    }


    public function reportProduct(Request $request, $id)
    {
        $userId = auth()->id();  
        $productId = $id;

        $alreadyReported = DB::table('reported_products')
            ->where('user_id', $userId)
            ->where('product_id', $productId)
            ->exists();

        if ($alreadyReported) {
            return response()->json([
                'status' => 'error',
                'message' => 'You have already reported this product.'
            ], 400);
        }

        // Report the product
        DB::table('reported_products')->insert([
            'user_id' => $userId,
            'product_id' => $productId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Product has been reported successfully!'
        ]);
    }
}
