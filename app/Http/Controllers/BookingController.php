<?php

namespace App\Http\Controllers;

use App\Models\AdminSetting;
use App\Models\Order;
use App\Models\ProductUnavailability;
use App\Models\{Query,ProductDisableDate};
use App\Models\Transaction;
use App\Notifications\BookorderReq;
use App\Traits\SmsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\PaymentIntent;

class BookingController extends Controller
{
    use SmsTrait;
    public function cardDetail($query = null, $price = null)
    {
        $query = jsdecode_userdata($query);
        $price = jsdecode_userdata($price);
        // dd($price,$queryId);
        $user = auth()->user();
        $stripeCustomer = $user->createOrGetStripeCustomer();
        $intent = $user->createSetupIntent();
        $insurance =  AdminSetting::where('key', 'insurance_fee')->first();
        $security =  AdminSetting::where('key', 'security_fee')->first();
        $queryData =  Query::with('forUser', 'product')->where('id', $query)->first();
        $dates = explode(' - ', $queryData->date_range);
        $startDate = date('Y-m-d', strtotime($dates[0]));
        $endDate = date('Y-m-d', strtotime($dates[1]));
        $disabledDates = ProductDisableDate::where('product_id',$queryData->product->id)->whereBetween('disable_date', [$startDate, $endDate])->get();
        if(!$disabledDates->isEmpty()){
            $queryData->update([
                'status' => 'REJECTED',
            ]);
            return redirect()->back()->with('error', ' This offer is not longer should available, please try some different product');
        }
        $identity_amount = AdminSetting::where('key','identity_commission')->pluck('value')->first();
        return view('customer.card_payment', compact('intent', 'query', 'price', 'insurance', 'security','identity_amount'));
    }

    public function charge(Request $request)
    {
        // dd($request->all());
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $user = auth()->user();
        $stripeCustomer = $user->createOrGetStripeCustomer();
        $query =  Query::with('forUser', 'product')->where('id', jsdecode_userdata($request->query_id))->first();
        // dd($query);
        $order_commission = AdminSetting::where('key', 'order_commission')->first();
        //testing

        $fromAndToDate = array_map('trim', explode(' - ', $query->date_range));

        $fromstartDate = date('Y-m-d', strtotime($fromAndToDate[0]));
        $fromendDate = date('Y-m-d', strtotime($fromAndToDate[1]));

        $identity_amount = AdminSetting::where('key','identity_commission')->pluck('value')->first();

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
        if(isset(auth()->user()->identity_status) && auth()->user()->identity_status=='unpaid')
        {
           $orderData['total'] =jsdecode_userdata($request->total_payment) - $identity_amount;
        //    $transactionData['total']  = jsdecode_userdata($request->total_payment) - $identity_amount;

        }else{
           $orderData['total'] =jsdecode_userdata($request->total_payment);
        //    $transactionData['total']  = jsdecode_userdata($request->total_payment);

        }

        $order = Order::create($orderData);
        $query->update([
            'status' => 'COMPLETED',
        ]);


        $dates = explode(' - ', $query->date_range);
            $startDate = date('Y-m-d', strtotime($dates[0]));
            $endDate = date('Y-m-d', strtotime($dates[1]));
        // dd($startDate ,$endDate);

            while ($startDate <= $endDate) {
                $startDate = date_create($startDate);
                $query->product->disableDates()->create([
                    'disable_date' => $startDate->format('Y-m-d'),
                ]);


                $startDate->modify('+1 day');
                $startDate = $startDate->format('Y-m-d');
            }


        // ProductDisableDate::create([
        //     'product_id' => $query->product_id,
        //     'order_id' => $order->id,
        //     'from_date' => $fromstartDate,
        //     'to_date' => $fromendDate,
        // ]);

        $date = date('Y-m-d H:i:s');
        $status = PaymentIntent::create([
            'amount' => floatval(jsdecode_userdata($request->total_payment)) * 100, // amount in cents
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
// dd($status);
        // testing
        // if (isset($query->negotiate_price)) {
        //     $amount = $query->negotiate_price * ($order_commission->value / 100);
        //     $dealerAmount = $request->total_payment - $amount;
        // } else {
        //     $amount = $query->getCalculatedPrice($query->date_range) * ($order_commission->value / 100);
        //     $dealerAmount = $request->total_payment - $amount;
        // }
        // $paymentIntent = PaymentIntent::create([
        //     'amount' => floatval($request->total_payment) * 100,
        //     'currency' => 'usd',
        //     'customer' => $stripeCustomer->id,
        //     'payment_method' => $request->token,
        //     'confirmation_method' => 'manual',
        //     'confirm' => true,
        //     'transfer_data' => [
        //         'destination' => $query->forUser->stripe_account_id,
        //         'amount' => $dealerAmount * 100,
        //     ],
        //     'return_url' => route('orders')
        // ]);


        $transactionData = [
            'payment_id' => $status->id,
            'user_id' => $user->id,
            'date' => $date,
            'status' => 'Completed',
            'gateway_response' => $status
        ];
        if(isset(auth()->user()->identity_status) && auth()->user()->identity_status=='unpaid')
        {
           $transactionData['total']  = jsdecode_userdata($request->total_payment) - $identity_amount;

        }else{
           $transactionData['total']  = jsdecode_userdata($request->total_payment);

        }
       $transaction= Transaction::create($transactionData);
        $user = auth()->user();
        $user->update([
            'identity_status'=>'paid'
        ]);
        DB::commit();
        $lender = $query->forUser;

        $product_info=[
            'id'=>$order->id,
            'customer_name'=>$user->name,
            'from_date' =>$fromstartDate,
            'to_date' =>$fromendDate,
            'lender_id'=>$query->for_user
        ];

        if(@$lender->usernotification->order_req == '1'){
            $lender->Notify(new BookorderReq($product_info));
            $otpMessage = [
                'message' => 'The booking for your product is done ' . $query->product->name .'from date ' .$product_info['from_date'] .'to ' .$product_info['to_date'],
                'route' => route('retailercustomer') // Optional link
            ];

            $phoneNumber = $lender->country_code . $lender->phone_number;

            $this->sendSms($phoneNumber, $otpMessage);
        }
        if ($status->status == "succeeded") {
            $order->update(['transaction_id' => $transaction->id]);
            return redirect()->route('orders')->with('success', 'Order placed successfully');
        }
    }
}
