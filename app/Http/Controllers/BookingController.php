<?php

namespace App\Http\Controllers;

use App\Models\AdminSetting;
use App\Models\Order;
use App\Models\ProductUnavailability;
use App\Models\Query;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\PaymentIntent;

class BookingController extends Controller
{
    public function cardDetail(Query $query = null, $price = null)
    {
        $user = auth()->user();
        $stripeCustomer = $user->createOrGetStripeCustomer();
        $intent = $user->createSetupIntent();
        $insurance =  AdminSetting::where('key', 'insurance_fee')->first();
        $security =  AdminSetting::where('key', 'security_fee')->first();
        return view('customer.card_payment', compact('intent', 'query', 'price', 'insurance', 'security'));
    }

    public function charge(Request $request)
    {
        // dd($request->total_payment);
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $user = auth()->user();
        $stripeCustomer = $user->createOrGetStripeCustomer();
        $query =  Query::where('id', $request->query_id)->first();

        //testing

        $fromAndToDate = array_map('trim', explode(' - ', $query->date_range));

        $fromstartDate = date('Y-m-d', strtotime($fromAndToDate[0]));
        $fromendDate = date('Y-m-d', strtotime($fromAndToDate[1]));
        // dd($request->token);
        // dd($fromAndToDate, $fromAndToDate[0], $fromAndToDate[1], $fromstartDate, $fromendDate);
        $orderData = [
            'user_id' => $user->id,
            'retailer_id' => $query->for_user,
            'transaction_id' => null,
            'query_id' => $query->id,
            'product_id' => $query->product_id,
            'from_date' => $fromstartDate,
            'to_date' => $fromendDate,
            'order_date' => date('Y-m-d H:i:s'),
            'pickedup_date' => null,
            'total' => $request->total_payment,
            'status' => 'Waiting',
            // 'security_option' => null,
            // 'security_option_type' => $request->securityOptionType,
            // 'security_option_value' => $request->securityOptionValue,
            // 'security_option_amount' => null,
            // 'customer_transaction_fee_type' => null,
            // 'customer_transaction_fee_value' => null,
            // 'customer_transaction_fee_amount' => null,
            // 'order_commission_type' => null,
            // 'order_commission_value' => null,
            'vendor_received_amount' => null,
            'order_commission_amount' => null,
        ];
        DB::beginTransaction();
        $order = Order::create($orderData);
        $query->update([
            'status' => 'COMPLETED',
        ]);

        ProductUnavailability::create([
            'product_id' => $query->product_id,
            'order_id' => $order->id,
            'from_date' => $fromstartDate,
            'to_date' => $fromendDate,
        ]);

        $date = date('Y-m-d H:i:s');

        $status = PaymentIntent::create([
            'amount' => floatval($request->total_payment) * 100, // amount in cents
            'currency' => 'usd',
            'customer' => $stripeCustomer->id,
            'payment_method' => $request->token,
            'confirmation_method' => 'manual',
            'confirm' => true,
            // 'description' => jsencode_userdata($cart->id),
            // 'metadata' => [
            //     'cart_id' => jsencode_userdata($cart->id), // Add your custom order ID as metadata
            // ],
            'return_url' => route('orders')
        ]);

        $transaction = Transaction::create([
            'payment_id' => $status->id,
            'user_id' => $user->id,
            'total' => $request->total_payment,
            'date' => $date,
            'status' => 'Completed',
            'gateway_response' => $status
        ]);
        DB::commit();

        if ($status->status == "succeeded") {
            $order->update(['transaction_id' => $transaction->id]);
            return redirect()->route('orders');
        }
    }
}
