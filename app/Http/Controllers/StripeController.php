<?php

namespace App\Http\Controllers;

use App\Http\Requests\{BillingRequest, CheckoutRequest};
use App\Http\Traits\ProductTrait;
use App\Notifications\AcceptItemReqLender;
use App\Notifications\LenderReceivebookingReq;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\{Country, Product, User, Order, OrderItem, ProductUnavailability, Transaction, BillingToken, CustomerBillingDetails, State, City, AdminSetting, ProductLocation, UserCard, NeighborhoodCity};
use App\Notifications\{CustomerBookingRequest, OrderPlaced, Payment, RejectOrderByLender, VendorOrderPlaced, LenderAcceptItemRequest, AcceptItemRequest, RentalCancelorder};
use Exception, Stripe, Auth, DB;

class StripeController extends Controller
{
    use ProductTrait;

    public function __construct()
    {
        $this->stripe = Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $this->stripeClient = new Stripe\StripeClient(env('STRIPE_SECRET'));
    }

    // checkout product and redirect to billing detail page
    public function checkout(Request $request, Product $product)
    {
        // dd($request->all());
        $product->load('locations');
        $product = $this->checkProductAvailability($request, $product->id);
        if (is_null($product)) {
            return response()->json([
                'status' => false,
                'title' => 'Reservation',
                'message' => __('product.messages.invalidReservationDate')
            ]);
        }
        $location = $this->productNearestLocation($request->latitude, $request->longitude, $product->id);
        $validateReserveDate = $this->validateReservationDate($request, $product);
        if (is_null($location) || is_null($product) || !is_array($validateReserveDate)) {
            if (!is_array($validateReserveDate)) {
                $message = __('product.messages.invalidReservationDate');
            } elseif (is_null($product)) {
                $message = __('product.messages.notAvailable');
            } else {
                $message = __('product.messages.notAvailableArea');
            }

            return response()->json([
                'status' => false,
                'title' => 'Reservation',
                'message' => $message
            ]);
        }
        $token = Str::random(32);
        $rentalDays = $validateReserveDate['rental_days'];
        $security = $this->getSecurityAmount($product);
        $insurance = $this->getInsuranceAmount($product);
        $transactionFee = $this->getTransactionAmount($product, $rentalDays);
        $securityOptionAmount = $total = $request->option == 'insurance' ? $insurance : $security;
        $total += $transactionFee + ($rentalDays * $product->rent);
        $total = number_format((float) $total, 2, '.', '');

        if ($total >= $this->maxTotalAmount) {
            return response()->json([
                'status' => false,
                'title' => 'Amount',
                'message' => 'Booking amount should not be greater than $' . $this->maxTotalAmount
            ]);
        }

        $adminSetting = AdminSetting::where('key', $request->option . '_fee')->first();
        $transactionSetting = AdminSetting::where('key', 'renter_transaction_fee')->first();
        if ("Hour" == $product->rentaltype) {
            // create token and redirect to billing page
            BillingToken::create([
                'token' => $token,
                'user_id' => auth()->user()->id,
                'product_id' => $product->id,
                'location_id' => $location->id,
                'map_location' => $request->customer_location,
                'map_latitude' => $request->latitude,
                'map_longitude' => $request->longitude,
                'from_date' => $validateReserveDate['from'],
                'to_date' => $validateReserveDate['to'],
                'from_hour' => $validateReserveDate['fromHour'],
                'from_minute' => $validateReserveDate['fromMinute'],
                'to_hour' => $validateReserveDate['toHour'],
                'to_minute' => $validateReserveDate['toMinute'],
                'rent' => $product->rent,
                'security' => $product->security,
                'security_option' => $request->option,
                'security_option_type' => $adminSetting->type,
                'security_option_value' => $adminSetting->value,
                'security_option_amount' => $securityOptionAmount,
                'customer_transaction_fee_type' => $transactionSetting->type,
                'customer_transaction_fee_value' => $transactionSetting->value,
                'customer_transaction_fee_amount' => $transactionFee

            ]);
        } else {
            $billing = BillingToken::create([
                'token' => $token,
                'user_id' => auth()->user()->id,
                'product_id' => $product->id,
                'location_id' => $location->id,
                'map_location' => $request->customer_location,
                'map_latitude' => '',
                'map_longitude' => '',
                'from_date' => $validateReserveDate['from'],
                'to_date' => $validateReserveDate['to'],
                'pickup_datetime' => $request->pickup_datetime,
                'rent' => $product->rent,
                'security' => $product->security,
                'security_option' => $request->option,
                'security_option_type' => $adminSetting->type,
                'security_option_value' => $adminSetting->value,
                'security_option_amount' => $securityOptionAmount,
                'customer_transaction_fee_type' => $transactionSetting->type,
                'customer_transaction_fee_value' => $transactionSetting->value,
                'customer_transaction_fee_amount' => $transactionFee,
            ]);
        }

        return redirect()->route('billing', [$billing->token]);
    }

    public function billing(Request $request, $token)
    {
        $billingDetail = $this->validateBillingToken($token);

        $product = Product::find($billingDetail->product_id);

        if (is_null($billingDetail)) {
            return response()->json([
                'status' => false,
                'title' => 'Token expire',
                'message' => __('product.messages.expiredToken')
            ]);
        }
        $billingDetail->load('location');

        if ("Hour" == $product->rentaltype) {

            // Change the date time formate global_date_format to global_product_blade_date_time_format
            $request->merge([
                'reservation_date' => date($request->global_product_blade_date_time_format, strtotime($billingDetail->from_date . ' ' . $billingDetail->from_hour . ':' . $billingDetail->from_minute)) . ' ' . $request->global_date_separator . ' ' . date($request->global_product_blade_date_time_format, strtotime($billingDetail->to_date . ' ' . $billingDetail->to_hour . ':' . $billingDetail->to_minute))
            ]);
        } else {

            $request->merge([
                'reservation_date' => date('m/d/Y', strtotime($billingDetail->from_date)) . ' ' . $request->global_date_separator . ' ' . date('m/d/Y', strtotime($billingDetail->to_date))
            ]);
        }

        $validateReserveDate = $this->validateReservationDate($request, $product);
        if ($validateReserveDate == 'fail' || is_null($product)) {
            return response()->json([
                'status' => false,
                'title' => 'Reservation Date',
                'message' => __('product.messages.invalidReservationDate')
            ]);
        }
        // get customer recent billing detail
        $customerBillingDetail = CustomerBillingDetails::where('user_id', auth()->user()->id)->orderByDesc('id')->first();
        if (is_null($customerBillingDetail)) {
            $customerBillingDetail = auth()->user();
        }

        $countries = Country::all();
        $states = State::where('country_id', $customerBillingDetail->country_id)->get();
        $cities = City::where('state_id', $customerBillingDetail->state_id)->get();
        $rentalDays = $validateReserveDate['rental_days'];
        $rentalAmount = number_format((float)($billingDetail->rent * $rentalDays), 2, '.', '');
        $security = $this->getSecurityAmount($product);
        $insurance = $this->getInsuranceAmount($product);
        $transactionFee = $this->getTransactionAmount($product, $rentalDays);
        $securityOptionAmount = $total = $billingDetail->security_option == 'insurance' ? $insurance : $security;
        $total = ($securityOptionAmount + $transactionFee) + ($rentalDays * $product->rent);
        $total = number_format((float) $total, 2, '.', '');

        $meta = [
            'product_id' => $billingDetail->product_id,
            'product_location' => $billingDetail->location_id,
            'customer_location' => $billingDetail->map_location,
            'reservation_date' => $request->reservation_date,
            'rental_amount' => $rentalAmount,
            'rental_days' => $rentalDays,
            'security_option' => ucfirst($billingDetail->security_option),
            'security_option_value' => $securityOptionAmount,
            'total' => $total,
            'billing_token' => $token,
            'transactionFee' => $transactionFee
        ];
        $cardlist = UserCard::where('user_id', auth()->user()->id)->get();

        return response()->json([
            'status'    =>  true,
            'data'      =>  [
                'html'  =>  view('payment-process', compact('billingDetail', 'meta', 'countries', 'states', 'cities', 'customerBillingDetail', 'cardlist'))->render(),
            ]
        ]);

        //return view('product-detail', compact('relatedProducts','product','insurance','security','billingDetail', 'meta', 'countries', 'states', 'cities', 'customerBillingDetail'))->with(['payment' => 1]);
    }

    // create customer for stripe and also create payment intent

    public function charge(Request $request)
    {
        // dd("dbvjvbjsdv", $request);
        $productId = $request->product_id;
        $product = $this->checkProductAvailability($request, $productId);
        $neighborhoodcity = NeighborhoodCity::where("id", $product->neighborhood_city)->first();

        $validateReserveDate = $this->validateReservationDate($request, $product);
        $validateToken = $this->validateBillingToken($request->billing_token);
        //    check card expiry
        $cardexpiry = UserCard::where('user_id', auth()->user()->id)->where('is_default', 1)->first();
        // if ($request->get_card_type == 1 && jsdecode_userdata($cardexpiry->exp_year) < date('Y')) {
        //     return redirect()->route('index')->with([
        //         'status' => false,
        //         'title' => 'Error',
        //         'message' => 'Card already has expired'
        //     ]);
        // }
        // end

        if (is_null($validateToken) || is_null($product) || !is_array($validateReserveDate)) {
            if (!is_array($validateReserveDate)) {
                $message = __('product.messages.invalidReservationDate');
            } elseif (is_null($product)) {
                $message = __('product.messages.notAvailable');
            } else {
                $message = __('product.messages.expiredToken');
            }

            return response()->json([
                'status' => false,
                'title' => 'Error',
                'message' => $message
            ]);
        }

        try {
            // dd($request);
            // $usercard = UserCard::where('card_token', $request->payment_method)->first();
            if ($request->get_card_type == 1) {
                $payment_method = jsdecode_userdata($request->payment_method);
            } else {
                $payment_method = $request->payment_method;
            }
            $user = Auth::user();
            $user->createOrGetStripeCustomer();
            $user->addPaymentMethod($payment_method);

            $customer_billing_details = [
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'address1' => isset(auth()->user()->userDetail) ? auth()->user()->userDetail->address1 : null,
                'address2' => isset(auth()->user()->userDetail) ? auth()->user()->userDetail->address2 : null,
                'phone_number' => auth()->user()->phone_number,
                'postcode' => auth()->user()->zipcode,
                'country_id' => isset(auth()->user()->userDetail) ? auth()->user()->userDetail->country_id : null,
                'state_id' => isset(auth()->user()->userDetail) ? auth()->user()->userDetail->state_id : null,
                'city_id' => isset(auth()->user()->userDetail) ? auth()->user()->userDetail->city_id : null,
                'user_id' => auth()->user()->id,
                'token_id' => $validateToken->id,
            ];

            // store the billing details
            $customerBillingDetail = CustomerBillingDetails::create($customer_billing_details);

            $securityOption = $validateToken->security_option;
            $securityOptionType = $validateToken->security_option_type;
            $rentalDays = $validateReserveDate['rental_days'];
            $productRent = $product->rent;
            $security = $this->getSecurityAmount($product);
            $insurance = $this->getInsuranceAmount($product);
            $transactionFee = $this->getTransactionAmount($product, $rentalDays);
            $securityOptionAmount = $total = ($securityOption == 'insurance') ? $insurance : $security;
            $total = ($securityOptionAmount + $transactionFee) + ($rentalDays * $productRent);
            $orderTotal = number_format((float) $total, 2, '.', '');
            $orderCommisionAdmin = $this->getOrderCommisionAmount($product);

            $adminSetting = AdminSetting::where('key', $securityOption . '_fee')->first();
            $transactionSetting = AdminSetting::where('key', 'renter_transaction_fee')->first();
            $orderCommission = $this->getVendorReceivedAmount($securityOptionAmount, $orderCommisionAdmin, $orderTotal);
            // dd("djvdvsbjvbdsvbv", $orderCommission);
            // create payment intent
            $paymentIntent = Stripe\PaymentIntent::create([
                'customer' => $user->stripe_id,
                'amount' => $orderTotal * 100,
                'currency' => 'usd',
                'payment_method' => $payment_method,
                'payment_method_types' => ['card'],
                'payment_method_options' => [
                    'card' => [
                        'request_three_d_secure' => 'any'
                    ]
                ],
                'metadata' => [
                    'requestProductId' => $request->product_id,
                    'requestProductLocationId' => $request->product_location,
                    'requestCustomerLocation' => $request->customer_location,
                    'requestReservationDate' => $request->reservation_date,
                    'requestFromDate' => $validateReserveDate['from'],
                    'requestToDate' => $validateReserveDate['to'],
                    'requestPickupDatetime' => $validateToken->pickup_datetime,
                    'requestRentalAmount' => $request->rental_amount,
                    'requestRentalDays' => $request->rental_days,
                    'requestTotal' => $request->total,
                    'billingToken' => $request->billing_token,
                    'customerBillingDetailId' => $customerBillingDetail->id,
                    'productId' => $product->id,
                    'productRent' => $productRent,
                    'orderTotal' => $orderTotal,
                    'securityOption' => $securityOption,
                    'securityOptionType' => $adminSetting->type,
                    'securityOptionValue' => $adminSetting->value,
                    'securityOptionAmount' => $securityOptionAmount,
                    'customerTransactionFeeType' => $transactionSetting->type,
                    'customerTransactionFeeValue' => $transactionSetting->value,
                    'customerTransactionFeeAmount' => $transactionFee,
                    'orderCommissionType' => $orderCommission["order_commission_type"],
                    'orderCommissionValue' => $orderCommission["order_commission_value"],
                    'vendorReceivedAmount' => $orderCommission["vendor_received_amount"],
                    'orderCommissionAmount' => $orderCommission["order_commission_amount"],
                ],
                'receipt_email' => auth()->user()->email,
            ]);
            $metaData = $paymentIntent->metadata;
            // order create
            $orderData = [
                'user_id' => $user->id,
                'location_id' => $metaData->requestProductLocationId,
                'from_date' => $metaData->requestFromDate,
                'to_date' => $metaData->requestToDate,
                'order_date' => date('Y-m-d H:i:s'),
                'pickedup_date' => $metaData->requestPickupDatetime,
                'total' => $metaData->orderTotal,
                'status' => 'Pending',
                'security_option' => $metaData->securityOption,
                'security_option_type' => $metaData->securityOptionType,
                'security_option_value' => $metaData->securityOptionValue,
                'security_option_amount' => $metaData->securityOptionAmount,
                'customer_transaction_fee_type' => $metaData->customerTransactionFeeType,
                'customer_transaction_fee_value' => $metaData->customerTransactionFeeValue,
                'customer_transaction_fee_amount' => $metaData->customerTransactionFeeAmount,
                'order_commission_type' => $metaData->orderCommissionType,
                'order_commission_value' => $metaData->orderCommissionValue,
                'vendor_received_amount' => $metaData->vendorReceivedAmount,
                'order_commission_amount' => $metaData->orderCommissionAmount,
            ];
            // end
            $order = Order::create($orderData);

            $productLocation = ProductLocation::findOrFail($metaData->requestProductLocationId);
            if (!is_null($order)) {
                $orderId = $order->id;
                // store order item
                $orderItem = OrderItem::create([
                    'order_id' => $orderId,
                    'product_id' => $metaData->productId,
                    'customer_id' => $user->id,
                    'retailer_id' => $product->user_id,
                    'rent_per_day' => $metaData->productRent,
                    'total_rental_days' => $metaData->requestRentalDays,
                    'date' => $validateToken->pickup_datetime,
                    'total' => $metaData->requestTotal,
                    'status' => 'Pending',
                    'vendor_received_amount' => $metaData->vendorReceivedAmount,
                    'location_id' => $productLocation->location_id,
                    'country' => $productLocation->country,
                    'state' => $productLocation->state,
                    'city' => $neighborhoodcity->name,
                    'map_address' => $productLocation->map_address,
                    'latitude' => $productLocation->latitude,
                    'longitude' => $productLocation->longitude,
                    'postcode' => $productLocation->postcode,
                    'custom_address' => $productLocation->custom_address
                ]);
            }

            if (!is_null($validateToken)) {
                $validateToken->update([
                    'order_id' => $orderId,
                ]);

                // $billingToken->delete();
            }

            $customerBillingDetail = CustomerBillingDetails::with('country', 'state', 'city')->where('id', $metaData->customerBillingDetailId)->first();
            if (!is_null($customerBillingDetail)) {
                $customerBillingDetail->update([
                    'order_id' => $orderId
                ]);
            }


            $paymentIntentId = $paymentIntent->id;
            $validateToken->update([
                'payment_intent_id' => $paymentIntentId,
            ]);

            // payment create
            // $confirmation = $this->stripeClient->paymentIntents->confirm($paymentIntentId, [
            //     'use_stripe_sdk' => false,
            //     'return_url' => route('paymentsuccess')
            // ]);

            // dd($confirmation, 'check confirmations');
            //end

            $user = User::with('cards', 'notification')->where('id', Auth::user()->id)->first();
            if ($request->savecard) {
                $request->validate([
                    'payment_method' => 'required|string',
                    // 'card_holder_name' => 'required'
                ]);

                $this->saveCard($request);
            }
            $transaction =  Transaction::where('order_id', $order->id)->first();
            $product = Product::with('thumbnailImage', 'retailer.notification')->findOrFail($metaData->productId);


            //  notification
            $emaildata = [
                'order' => $order,
                'product' => $product,
                'order_item' => $orderItem
            ];

            // if (isset($user->notification) && $user->notification->user_booking_request == "on") {
            $user->notify(new CustomerBookingRequest($emaildata, $user));
            // }
            $retaileruser = $emaildata['product']['retailer'];
            // User::where()->first();

            // if (isset($retaileruser->notification) && $retaileruser->notification->lender_receives_booking_request == "on") {
            $retaileruser->notify(new LenderReceivebookingReq($emaildata, $user));
            // }
            // if (@$user->notification->order_placed == 'on') {
            //     $user->notify(new OrderPlaced($emailData));
            // }

            // end notification

            // if (isset($confirmation['next_action']['redirect_to_url']['url'])) {
            //     $return_url = $confirmation['next_action']['redirect_to_url']['url'];
            // }
            // if (isset($confirmation['charges']['data']['0']['source']['redirect']['url'])) {
            //     $return_url = $confirmation['charges']['data']['0']['source']['redirect']['url'];
            // }

            // //  Add card 
            // dd('hghghgvhgvhvh', $confirmation);
            session()->flash('success', "Your rental request has been sent!");
            return redirect()->route('index');
        } catch (Exception $ex) {

            return redirect()->route('viewproduct', [jsencode_userdata($product->id)])->with('error', $ex->getMessage());
        }
    }

    private function saveCard(Request $request)
    {
        // dd($request);
        try {
            $user = Auth::user();
            $paymentMethod = $user->addPaymentMethod($request->payment_method);
            $defaultCard = 0;
            if (0 == $user->cards->count()) {
                $defaultCard = 1;
            }

            $user = $user->cards()->updateOrCreate([
                'user_id' => $user->id,
                'fingerprint' => jsencode_userdata($paymentMethod->card->fingerprint),
            ], [
                'card_token' => jsencode_userdata($paymentMethod->id),
                'brand' => $paymentMethod->card->brand,
                'exp_month' => jsencode_userdata($paymentMethod->card->exp_month),
                'exp_year' => jsencode_userdata($paymentMethod->card->exp_year),
                'last_digits' => jsencode_userdata($paymentMethod->card->last4),
                'is_default' => $defaultCard,
            ]);
            return $user;
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage());
        }
    }

    public function payment_transaction(Request $request)
    {
        // dd($request->toArray(), 'payment_transaction');
        try {


            $paymentIntent = $request->payment_intent_id;
            // payment create
            $confirmation = $this->stripeClient->paymentIntents->confirm($paymentIntent, [
                'use_stripe_sdk' => false,
                'return_url' => route('paymentsuccess')
            ]);

            $metaData = $confirmation->metadata;
            $user = User::with('notification')->where('email', $confirmation->receipt_email)->first();
            $product = Product::with('thumbnailImage', 'retailer.notification')->findOrFail($metaData->productId);
            $billing = BillingToken::where('token', $metaData->billingToken)->first();
            $order = Order::where('id', $billing->order_id)->first();
            $orderItem = OrderItem::where('order_id', $order->id)->first();
            $emaildata = [
                'order' => $order,
                'product' => $product,
                'order_item' => $orderItem
            ];

            $retaileruser = $emaildata['product']['retailer'];

            if (isset($user->notification) && $user->notification->lender_accept_booking_request == "on") {
                $user->notify(new LenderAcceptItemRequest($emaildata, $user));
            }
            if (@$retaileruser->notification->lender_accept_booking_request == "on") {
                $retaileruser->notify(new AcceptItemReqLender($emaildata, $user));
            }

            // dd($confirmation['next_action']['redirect_to_url']['url'], $confirmation['charges']['data']['0']['source']['redirect']['url'], $confirmation);
            if (isset($confirmation['next_action']['redirect_to_url']['url'])) {
                $return_url = $confirmation['next_action']['redirect_to_url']['url'];
            }
            if (isset($confirmation['charges']['data']['0']['source']['redirect']['url'])) {
                $return_url = $confirmation['charges']['data']['0']['source']['redirect']['url'];
            }

            return response()->json([
                'success'    =>  true,
                'url'       =>   $return_url
            ], 200);
            // return redirect($return_url);
        } catch (\Exception $e) {
            return response()->json([
                'success'    =>  false,
                'msg'      =>  $e->getMessage()
            ]);
        }
    }

    // when payment done successfully On Day
    public function success(Request $request)
    {
        // dd($request);
        try {
            $paymentIntent = $request->payment_intent;
            $response = $this->stripeClient->paymentIntents->retrieve(
                $paymentIntent,
                []
            );

            $metaData = $response->metadata;
            $billing = BillingToken::where('payment_intent_id', $paymentIntent)->first();
            $order = Order::where('id', $billing->order_id)->first();
            $user = User::where('id', $order->user->id)->first();
            $orderItem = OrderItem::where('order_id', $order->id)->first();
            $transaction =  Transaction::where('order_id', $order->id)->first();
            $customerBillingDetail = CustomerBillingDetails::with('country', 'state', 'city')->where('id', $metaData->customerBillingDetailId)->first();

            // dd('get response', $response, $billing);

            if (isset($response->status) && $response->status == 'succeeded') {

                $paymentStatus = 'Completed';
                $orderStatus = 'Pending';
                // $user = auth()->user();
                $date = date('Y-m-d H:i:s');
                $metaData = $response->metadata;
                $orderId = $order->id;
                // DB::beginTransaction();
                $product = Product::with('thumbnailImage', 'retailer.notification')->findOrFail($metaData->productId);
                $productLocation = ProductLocation::findOrFail($metaData->requestProductLocationId);

                // if ('Hour' == $product->rentaltype) {
                //     $orderData = [
                //         'user_id' => $user->id,
                //         'location_id' => $metaData->requestProductLocationId,
                //         'from_date' => $metaData->requestFromDate,
                //         'to_date' => $metaData->requestToDate,
                //         'from_hour' => $metaData->requestFromHour,
                //         'from_minute' => $metaData->requestFromMinute,
                //         'to_hour' => $metaData->requestToHour,
                //         'to_minute' => $metaData->requestToMinute,
                //         'order_date' => $date,
                //         'total' => $metaData->orderTotal,
                //         'status' => $orderStatus,
                //         'order_status' => '1',
                //         'security_option' => $metaData->securityOption,
                //         'security_option_type' => $metaData->securityOptionType,
                //         'security_option_value' => $metaData->securityOptionValue,
                //         'security_option_amount' => $metaData->securityOptionAmount,
                //         'customer_transaction_fee_type' => $metaData->customerTransactionFeeType,
                //         'customer_transaction_fee_value' => $metaData->customerTransactionFeeValue,
                //         'customer_transaction_fee_amount' => $metaData->customerTransactionFeeAmount,
                //         'order_commission_type' => $metaData->orderCommissionType,
                //         'order_commission_value' => $metaData->orderCommissionValue,
                //         'vendor_received_amount' => $metaData->vendorReceivedAmount,
                //         'order_commission_amount' => $metaData->orderCommissionAmount,
                //     ];
                // } else {
                //     $orderData = [
                //         'user_id' => $user->id,
                //         'location_id' => $metaData->requestProductLocationId,
                //         'from_date' => $metaData->requestFromDate,
                //         'to_date' => $metaData->requestToDate,
                //         'order_date' => $date,
                //         'pickedup_date' => $metaData->requestPickupDatetime,
                //         'total' => $metaData->orderTotal,
                //         'status' => $orderStatus,
                //         'order_status' => '1',
                //         'security_option' => $metaData->securityOption,
                //         'security_option_type' => $metaData->securityOptionType,
                //         'security_option_value' => $metaData->securityOptionValue,
                //         'security_option_amount' => $metaData->securityOptionAmount,
                //         'customer_transaction_fee_type' => $metaData->customerTransactionFeeType,
                //         'customer_transaction_fee_value' => $metaData->customerTransactionFeeValue,
                //         'customer_transaction_fee_amount' => $metaData->customerTransactionFeeAmount,
                //         'order_commission_type' => $metaData->orderCommissionType,
                //         'order_commission_value' => $metaData->orderCommissionValue,
                //         'vendor_received_amount' => $metaData->vendorReceivedAmount,
                //         'order_commission_amount' => $metaData->orderCommissionAmount,
                //     ];
                // }
                // $order = Order::create($orderData);

                // if (!is_null($order)) {
                //     $orderId = $order->id;
                //     // store order item
                //     $orderItem = OrderItem::create([
                //         'order_id' => $orderId,
                //         'product_id' => $metaData->productId,
                //         'customer_id' => $user->id,
                //         'retailer_id' => $product->user_id,
                //         'rent_per_day' => $metaData->productRent,
                //         'total_rental_days' => $metaData->requestRentalDays,
                //         'date' => $date,
                //         'total' => $metaData->requestTotal,
                //         'status' => $orderStatus,
                //         'vendor_received_amount' => $metaData->vendorReceivedAmount,
                //         'location_id' => $productLocation->location_id,
                //         'country' => $productLocation->country,
                //         'state' => $productLocation->state,
                //         'city' => $productLocation->city,
                //         'map_address' => $productLocation->map_address,
                //         'latitude' => $productLocation->latitude,
                //         'longitude' => $productLocation->longitude,
                //         'postcode' => $productLocation->postcode,
                //         'custom_address' => $productLocation->custom_address
                //     ]);

                //     // store transaction
                $transaction = Transaction::create([
                    'payment_id' => $paymentIntent,
                    'order_id' => $orderId,
                    'user_id' => $user->id,
                    'total' => $metaData->orderTotal,
                    'date' => $date,
                    'status' => $paymentStatus,
                    'gateway_response' => $response
                ]);

                $order->update(['transaction_id' => $transaction->id]);

                if ('Hour' == $product->rentaltype) {
                    ProductUnavailability::create([
                        'product_id' => $metaData->productId,
                        'order_id' => $orderId,
                        'from_date' => $metaData->requestFromDate,
                        'to_date' => $metaData->requestToDate,
                        'from_hour' => $metaData->requestFromHour,
                        'from_minute' => $metaData->requestFromMinute,
                        'to_hour' => $metaData->requestToHour,
                        'to_minute' => $metaData->requestToMinute,
                    ]);
                } else {
                    ProductUnavailability::create([
                        'product_id' => $metaData->productId,
                        'order_id' => $orderId,
                        'from_date' => $metaData->requestFromDate,
                        'to_date' => $metaData->requestToDate,
                    ]);
                }

                //     // remove token
                //     $billingToken = BillingToken::where('token', $metaData->billingToken)->first();

                //     if (!is_null($billingToken)) {
                //         $billingToken->update([
                //             'order_id' => $orderId,
                //         ]);

                //         // $billingToken->delete();
                //     }

                //     $customerBillingDetail = CustomerBillingDetails::with('country', 'state', 'city')->where('id', $metaData->customerBillingDetailId)->first();
                //     if (!is_null($customerBillingDetail)) {
                //         $customerBillingDetail->update([
                //             'order_id' => $orderId
                //         ]);
                //     }

                // send notification to retailer and customer
                $data = [
                    [
                        'order_id' => $order->id,
                        'sender_id' => null,
                        'receiver_id' => $order->user_id,
                        'action_type' => 'Order Payment',
                        'created_at' => $date,
                        'message' => 'The Payment of $' . $order->total . ' has been paid successfully for the order #' . $order->id
                    ],
                    [
                        'order_id' => $order->id,
                        'sender_id' => $user->id,
                        'receiver_id' => $product->user_id,
                        'action_type' => 'Order Placed',
                        'created_at' => $date,
                        'message' => 'A new order #' . $order->id . ' has been placed to you'
                    ], [
                        'order_id' => $order->id,
                        'sender_id' => null,
                        'receiver_id' => $order->user_id,
                        'action_type' => 'Order Placed',
                        'created_at' => $date,
                        'message' => 'Your order #' . $order->id . ' has been placed successfully'
                    ]
                ];

                $this->sendNotification($data);
            }
            // DB::commit();
            // dd('check');
            $emailData = [
                'order' => $order,
                'product' => $product,
                'transaction' => $transaction,
                'billing_detail' => $customerBillingDetail,
                'order_item' => $orderItem
            ];
            // check the payment status before sending the notification
            if (isset($user->notification) && @$user->notification->order_placed == 'on') {
                $user->notify(new OrderPlaced($emailData));
            }
            // if (@$user->notification->payment == 'on') {
            //     $user->notify(new Payment($emailData));
            // }


            // check the order status before sending the notification to vendor
            if (@$product->retailer->notification->order_placed == 'on') {
                $product->retailer->notify(new VendorOrderPlaced($emailData));
            }

            return redirect()->route('retailervieworder', ["order" => $order->id]);
            // } elseif (isset($response->metadata)) {
            //     $metaData = $response->metadata;
            //     $errorMessage = (isset($response->last_payment_error) && isset($response->last_payment_error->message)) ? $response->last_payment_error->message : 'Payment Failed';

            //     session(['billing_token' => $metaData->billingToken]);
            //     session(['payment_status' => $response->status]);
            //     session(['payment_message' => $errorMessage]);

            //     return redirect()->route('paymentfailed');
            // }

            // return redirect()->route('products')->with('error', 'Oops something went wrong');
        } catch (Exception $ex) {
            // dd($ex);
            return redirect()->back()->with('error', $ex->getMessage());

            // return redirect()->route('products')->with('error', $ex->getMessage());
        }
    }
    public function reject_order(Request $request)
    {
        $billingToken = BillingToken::where('payment_intent_id', $request->payment_intent_id)->first();
        $order = Order::where('id', $billingToken->order_id)->first();
        $user = User::where('id', $order->user_id)->first();
        $order->update([
            'status' => 'Cancelled',
            'order_status' => '2'
        ]);
        $order_item = OrderItem::where('order_id', $order->id)->first();
        $order_item->update([
            'status' => 'Cancelled',
        ]);
        $product = Product::with('thumbnailImage', 'retailer.notification')->where('id', $order_item->product_id)->first();
        $emaildata = [
            'order' => $order,
            'order_item' => $order_item,
            'product' => $product,
        ];

        $user->notify(new RentalCancelorder($emaildata, $user));
        $billingToken->delete();
        return response()->json(['success' => true], 200);
    }
    // payment failed
    public function failed(Request $request)
    {
        if (
            is_null(session('payment_message')) ||
            is_null(session('billing_token')) ||
            is_null(session('payment_status'))
        ) {
            return redirect()->route('products')->with('error', 'Oops! Something went wrong');
        }

        $message = session('payment_message');
        $token = session('billing_token');
        $status = session('payment_status');

        session()->forget('payment_message');
        session()->forget('billing_token');
        session()->forget('payment_status');

        return view('customer.payment_failed', compact('message', 'token', 'status'));
    }
}
