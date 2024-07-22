<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Query;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class LenderController extends Controller
{
    public function index(Request $request, $id)
{
    try {

        $userDetails = User::with(['userDetail'])->whereId($id)->firstOrFail();

        $products = Product::with(['locations', 'allImages', 'thumbnailImage', 'get_size', 'favorites', 'category', 'disableDates', 'get_brand', 'get_color'])
            ->where('user_id', $id)
            ->get();

        $transformedProducts = $products->map(function ($product) {
            $allImages = $product->allImages->map(function ($image) {
                return [
                    'id' => $image->id,
                    'product_id' => $image->product_id,
                    'file_name' => $image->file_name,
                    'file_path' =>  $image->file_path,
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
                'product_image_url' => $product->thumbnailImage ? $product->thumbnailImage->file_path : null,
                'all_images' => $allImages,
                'favourites' => !is_null($product->favorites) ? true : false,
            ];
        })->toArray();

        return response()->json([
            'status' => true,
            'message' => 'Products fetched successfully!',
            'data' => [
                'user' => $userDetails,
                'products' => $transformedProducts
            ],
        ], 200);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to fetch user details. Error: ' . $e->getMessage(),
            'errors' => [],
        ], 500);
    }
}




    private function getProduct($id)
    {
        if ($id) {
            return Product::with(['locations', 'allImages', 'thumbnailImage', 'get_size', 'favorites', 'category', 'disableDates', 'retailer'])
                ->where('user_id', $id)
                ->first()
                ->makeVisible(['file_path']);
        }
        return null;
    }

    // private function getInsuranceCost($productId)
    // {
    //     return Insurance::where('product_id', $productId)->first()->cost ?? 0;
    // }

    private function getSecurityDeposit($productId)
    {

        return Product::where('id', $productId)->first()->security_deposit ?? 0;
    }

    public function confirmPayment(Request $request, $bookingId)
    {
        try{

            // dd('here');
            $validator=Validator::make($request->all(),[
                'payment_method_id' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }


            $user = auth()->user();
            // dd($user);

            $booking = Query::where('id', $bookingId)->where('user_id', $user->id)->firstOrFail();
            $productDetails =Product::where('id', $booking->product_id)->first();

            Stripe::setApiKey(env('STRIPE_SECRET'));

            $paymentIntent = PaymentIntent::create([
                'amount' => $booking->price * 100 ?? $productDetails->getCalculatedPrice($booking->date_range) *100,
                'currency' => 'usd',
                'payment_method' => $request->payment_method_id,
                'confirmation_method' => 'manual',
                'confirm' => true,
            ]);
            dd($paymentIntent);

            $booking->update(['status' => 'CONFIRMED']);

            return response()->json([
                'status' => true,
                'message' => 'Payment confirmed and booking completed',
                'data' => $booking,
            ], 200);
        }
        catch (Exception $e) {
            return response()->json([
                'status'=> false,
                'message'=> $e->getMessage(),
                ],500);
            }
    }
}

