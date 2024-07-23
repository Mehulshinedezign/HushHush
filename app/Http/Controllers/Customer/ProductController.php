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
        $selectedCategories = (isset($request->category)) ? $request->category : [];
        $selectedcolor = (isset($request->filtercolor)) ? $request->filtercolor : [];
        $selectedcondition = (isset($request->condition)) ? $request->condition : [];
        $selectedbrands = (isset($request->brand)) ? $request->brand : [];
        $selectedsize = (isset($request->size)) ? $request->size : [];
        $rentalType = $request->rental_type ?? '';
        $product = $this->getProducts($request);
        $products = $product->get();

        $city = (isset($request->neighborhoodcity)) ? $request->neighborhoodcity : [];
        // dd($request->neighborhoodcity,  $city);

        // dd($request->global_product_pagination);
        // $allProducts = $products->get();
        // $products = $products->paginate($request->global_product_pagination);
        // $maxPrice = number_format((float)ceil(Product::max('rent')), 2, '.', '');
        // $minPrice = number_format((float)floor(Product::min('rent')), 2, '.', '');
        // $fourStar = $allProducts->whereBetween('average_rating', [3.1, 5])->count();
        // $threeStar = $allProducts->whereBetween('average_rating', [2.1, 3])->count();
        // $twoStar = $allProducts->whereBetween('average_rating', [1.1, 2])->count();
        // $oneStar = $allProducts->whereBetween('average_rating', [0.1, 1])->count();
        // $filters = [
        //     // 'maxprice' => $maxPrice,
        //     // 'minprice' => $minPrice,
        //     'categories' => $selectedCategories,
        //     'selectedcolor' => $selectedcolor,
        //     'selectedcondition' => $selectedcondition,
        //     'selectedbrands' => $selectedbrands,
        //     'selectedsize' => $selectedsize,
        //     'rental_type' => [$rentalType],
        //     // 'rent' => $request->rent ?? $maxPrice,
        //     'star1' => $oneStar,
        //     'star2' => $twoStar,
        //     'star3' => $threeStar,
        //     'star4' => $fourStar,
        //     'neighborhoodcity' => $city,
        // ];
        // dd($products, $filters, $filters['categories']);

        // dd($products);
        return view('index', compact('products', 'categories'))->with(['selectedLocation' => $this->selectedLocation]);
        // return view('index', compact('products', 'categories', 'filters',))->with(['selectedLocation' => $this->selectedLocation]);
    }

    /**
     * Display a product detail
     *
     * @return \Illuminate\Http\Response
     */
    // public function view(Request $request, $id)
    // {

      
    //     $id = jsdecode_userdata($id);
    //     // dd("TODAY prodcut id is : ",$id);
    //     $product = $this->getProduct($request, $id);
    //     if (is_null($product)) {
    //         return redirect()->back()->with('message', __('product.messages.notAvailable'));
    //     }
    //     $security = $this->getSecurityAmount($product);
    //     $insurance = $this->getInsuranceAmount($product);
    //     $rating_progress = $this->getratingprogress($product);
    //     $relatedProducts = Product::with('thumbnailImage', 'ratings', 'favorites')
    //         ->where('id', '<>', $product->id)
    //         ->where('category_id', $product->category_id)->whereHas('category', function ($q) {
    //             $q->where('status', '1');
    //         })
    //         ->where('user_id', '!=', auth()->user()->id)
    //         ->inRandomOrder()
    //         ->limit(5)
    //         ->get();

    //     $layout_class = 'single_product';


    //     $neighborhoodcity =   NeighborhoodCity::where('id', $product->neighborhood_city)->first();
    //     $city =   NeighborhoodCity::where('id', $neighborhoodcity->parent_id)->first();

    //     // $nonDates = json_encode($nonDates);


    //     return view('product-detail', compact('product', 'relatedProducts', 'security', 'insurance', 'layout_class', 'rating_progress', 'neighborhoodcity', 'city'))->with(['selectedLocation' => $this->selectedLocation]);
    // }

    public function view(Request $request, $id)
    {

      
        $id = jsdecode_userdata($id);

        $product = $this->getProduct($request, $id);
        if (is_null($product)) {
            return redirect()->back()->with('message', __('product.messages.notAvailable'));
        }
        $security = $this->getSecurityAmount($product);
        $insurance = $this->getInsuranceAmount($product);
        $rating_progress = $this->getratingprogress($product);
        $relatedProducts = Product::with('thumbnailImage', 'ratings', 'favorites')
            ->where('id', '<>', $product->id)
            ->where('category_id', $product->category_id)->whereHas('category', function ($q) {
                $q->where('status', '1');
            })
            ->where('user_id', '!=', auth()->user()->id)
            ->inRandomOrder()
            ->limit(5)
            ->get();

        $layout_class = 'single_product';


        $productImages = $product->allImages;
        // dd("DONE",$product);
        $querydates = Query::where('user_id',auth()->user()->id,'PENDING')->get();
            
        return view('product-detail', compact('product','productImages','querydates'));
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
    // public function wishlist(Request $request)
    // {
    //     $products = ProductFavorite::with('product.thumbnailImage', 'product.category')->where('user_id', auth()->user()->id)->orderByDesc('id')->paginate($request->global_product_pagination);
    //     $user = User::with('notification')->where('id', auth()->user()->id)->first();
    //     if (!$products->isEmpty() && @$user->notification->item_we_think_you_might_like == "on") {
    //         $user->notify(new ItemYouLike($user, $products));
    //     }

    //     return view('customer.wishlist', compact('products'));
    // }

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
        // $product1 = $this->checkTimeAvailablity($request, $id);  
        // dd($request->toArray(),$id, $product1);
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

    public function lenderInfo(Request $request,$id){

        
        $retailer = User::whereId(jsdecode_userdata($id))->first();
        $products = Product::with('ratings', 'thumbnailImage')->where('user_id', $retailer->id)->paginate($request->global_pagination);
        $ratedProducts = $products->where('average_rating', '>', '0');
        $averageRating = 0.0;
        if (count($ratedProducts)) {
            $averageRating = $ratedProducts->sum('average_rating') / count($ratedProducts);
        }

        return view('customer.profile',compact('products'));
    }
    
}
