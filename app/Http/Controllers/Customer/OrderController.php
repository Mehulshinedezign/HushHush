<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\RetailerPayout;
use App\Notifications\RentalCancelorder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\{ChatRequest, DisputeRequest, OrderPickUpReturnRequest, RatingRequest};
use App\Models\{AdminSetting, Order, Chat, OrderImage, ProductRating, OrderItem, Transaction, DisputeOrder, ProductUnavailability, User, Product, NeighborhoodCity, ProductDisableDate, Query, Refund};
use App\Notifications\{OrderCancelled, OrderPickUp, OrderReturn, VendorOrderCancelled, VendorOrderPickedUp, VendorOrderReturn, CustomerExperience, CustomerOrderPickup, CutomerOrderReturn, LenderImageUpload, LenderImageUploadForReturn, LenderOrderPickup, LenderOrderReturn, RefundCustomerSecuirty, RentalFeedback, RefundSecurity, RentalComplete};
use Carbon\Carbon;
use Stripe, Exception, DateTime;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // dd($request->toArray());
        $fromDate = $toDate = null;
        if (isset($request->global_date_separator) && isset($request->reservation_date)) {
            $fromAndToDate = array_map('trim', explode($request->global_date_separator, $request->reservation_date));
            // dd($fromAndToDate);
            $fromstartDate = date($request->global_date_format_for_check, strtotime($fromAndToDate[0]));
            $fromendDate = date($request->global_date_format_for_check, strtotime($fromAndToDate[1]));

            if (count($fromAndToDate) == 2) {
                $fromDate = DateTime::createFromFormat($request->global_date_format_for_check, $fromstartDate)->format('Y-m-d');
                $toDate = DateTime::createFromFormat($request->global_date_format_for_check, $fromendDate)->format('Y-m-d');
            }
        }
        // dd($fromDate, $toDate);
        $orders = Order::with('product.thumbnailImage', 'product.retailer', 'product.ratings', 'product.category', 'transaction', 'customerquery', 'product.nonAvailableDates')
            ->when((!is_null($request->status) && $request->status != 'all' && $request->status != 'disputed'), function ($q) use ($request) {
                $q->where('status', $request->status);
                $q->whereIn('dispute_status', ['No', 'Resolved']);
            })
            ->when((!is_null($request->status) && $request->status == 'disputed'), function ($q) {
                $q->where('dispute_status', 'Yes');
            })
            ->when((!is_null($request->order) && $request->order > 0), function ($q) use ($request) {
                $q->where('id', $request->order);
            })
            ->when(!is_null($fromDate) && !is_null($toDate), function ($q) use ($fromDate, $toDate) {
                $q->whereDate('from_date', '>=', $fromDate);
                $q->whereDate('to_date', '<=', $toDate);
            })
            ->where('user_id', auth()->user()->id)
            ->orderByDesc('id')
            ->paginate($request->global_pagination);




        return view('customer.order_history', compact('orders'));
    }

    public function payment_history(Request $request)
    {
        $payment_history = Order::with('item.product.thumbnailImage')->where('user_id', auth()->user()->id)->orderByDesc('id')->paginate($request->global_pagination);
        // return view('customer.payment_history', compact('payment_history'));

        return view('customer.payment_history');
    }

    public function viewOrder(Order $order)
    {
        $order->load(['user', 'rating', 'location', 'item.product', 'transaction', 'customerPickedUpImages', 'customerReturnedImages', 'retailerPickedUpImages', 'retailerReturnedImages', 'disputedOrderImages']);
        // $transaction = Transaction::where('order_id', $order->id)->first();

        // $neighborhoodcity = NeighborhoodCity::where('id', $order->item->product->neighborhood_city)->first();
        // return view('customer.order_detail', compact('order'));
        return view('customer.order_view', compact('order'));
    }

    public function orderChat(Request $request, $order = null)
    {
        // dd($request , $order);
        if ($order != 'order') {
            $order = Order::where('id', $order)->first();
        }
        //config(['database.connections.mysql.strict' => false]);
        $chatlist = OrderItem::with(['product', 'getchat'])->whereCustomerId(auth()->user()->id)->get();
        // dd($chatlist->toArray());
        //$chats = Chat::with('retailer')->chat($order->id)->get();

        return view('customer.order_chat', compact('order', 'chatlist'));
    }

    public function saveChat(ChatRequest $request, Order $order)
    {
        $userId = auth()->user()->id;
        $data = [
            'message' => $request->message,
            'order_id' => $order->id,
            'user_id' => $userId,
            'retailer_id' => $order->item->retailer_id,
            'sent_by' => 'Customer',
        ];

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('chats', 's3');
            $url = Storage::disk('s3')->url($path);
            $data['file'] = $path;
            $data['url'] = $url;
        }
        $chat = Chat::create($data);
        $chatHtml = view('customer.chat_message', compact('chat', 'order'))->render();

        return response()->json(['title' => __('order.success'), 'data' => $chatHtml, 'message' => __('order.messages.success')]);
    }

    public function orderSuccess(Order $order)
    {
        $order->load(['location', 'item.product']);
        return view('customer.order_success')->with('order', $order);
    }

    public function orderPickUp(OrderPickUpReturnRequest $request, Order $order)
    {
        if ('Yes' == $order->dispute_status || 'Resolved' == $order->dispute_status) {
            return redirect()->back()->with("warning", "You can not pickedup the dispute order");
        }

        if ($order->status != "Waiting") {
            return redirect()->back()->with("warning", 'Order must be in waiting state to upload the images.');
        }
        $order->load(['retailer','product']);
        if ($order->retailer_confirmed_pickedup == 0) {
            $userId = auth()->user()->id;
            $removedImageIds = explode(',', $request->removed_images);
            $images = [];
            foreach ($request->images as $file) {
                // dd($request);
                // if ($request->hasFile('image' . $i)) {
                //     $image = s3_store_image($request->file('image' . $i), 'products/images');
                if ($file != null) {
                    $images[] = [
                        'order_id' => $order->id,
                        'user_id' => $userId,
                        'url' => Storage::disk('public')->put('orders/pickup', $file),
                        'file' => $file->getClientOriginalName(),
                        'type' => 'pickedup',
                        'uploaded_by' => 'customer',
                    ];
                }
                // $file = $request->file('image'.$i);
                // $path = $file->store('orders/pickup', 's3');
                // $url = Storage::disk('s3')->url($path);
                // $images[] = [
                //     'order_id' => $order->id,
                //     'user_id' => $userId,
                //     'url' => $url,
                //     'file' => $path,
                //     'type' => 'pickedup',
                //     'uploaded_by' => 'customer',
                // ];
            }


            // if (count($images) || count($removedImageIds)) {
            //     $orderImages = OrderImage::where('order_id', $order->id)->whereIn('id', $removedImageIds)->where('user_id', $userId)->where('type', 'pickedup')->where('uploaded_by', 'customer')->get();
            //     foreach ($orderImages as $orderImage) {
            //         Storage::disk('s3')->delete('products/images' . $orderImage->file);
            //         // $orderImage->delete();
            //     }

            if (count($images)) {
                OrderImage::insert($images);
            }
            // }
            $customer_name = $order->user->name;
            $retailer = $order->retailer;
            $retailer->notify(new LenderImageUpload($customer_name , $order));
            return redirect()->back()->with('success', 'Images uploaded successfully');
        }

        return redirect()->route('vieworder', [$order->id]);
    }

    // customer confirm pickup
    public function confirmPickUp(Order $order)
    {
        if ('Yes' == $order->dispute_status || 'Resolved' == $order->dispute_status) {
            return redirect()->back()->with("warning", "You can not confirm the dispute order pickup");
        }

        if ($order->status != "Waiting") {
            return redirect()->back()->with("warning", 'Order must be in waiting state to confirm the pick up');
        }

        $image = OrderImage::where('order_id', $order->id)->where('type', 'pickedup')->where('uploaded_by', 'retailer')->first();
        if (!is_null($image) && $order->customer_confirmed_pickedup == 0) {
            $dateTime = date('Y-m-d H:i:s');
            $data = [
                'customer_confirmed_pickedup' => '1',
            ];

            // check is customer confirmed the pickup then change order status to picked up
            if ($order->retailer_confirmed_pickedup == '1') {
                $data['status'] = 'Picked Up';
                $data['pickedup_date'] = $dateTime;
                Order::where("id", $order->id)->update(["status" => "Picked Up"]);
                $user = auth()->user();

                $customer_info = [
                    'order_id'=>$order->id,
                    'product_name'=>$order->product->name,
                    'user_name' => $order->user->name,
                    'from_date' => $order->from_date,
                    'to_date' => $order->to_date,
                    'pickup_location' => $order->product->productCompleteLocation->pick_up_location,
                ];

                $lender_info = [
                    'order_id'=>$order->id,
                    'product_name'=>$order->product->name,
                    'lender_name' => $order->retailer->name,
                    'from_date' => $order->from_date,
                    'to_date' => $order->to_date,
                    'pickup_location' => $order->product->productCompleteLocation->pick_up_location,

                ];
                if (@$order->user->usernotification->customer_order_pickup == '1') {
                    $order->user->notify(new CustomerOrderPickup($customer_info));
                }
                if (@$order->retailer->usernotification->lender_order_pickup == '1') {
                    $order->retailer->notify(new LenderOrderPickup($lender_info));
                }
            }

            $order->update($data);

            // $data = [
            //     [
            //         'order_id' => $order->id,
            //         'sender_id' => $order->user_id,
            //         'receiver_id' => $order->item->retailer->id,
            //         'action_type' => 'Order Picked Up',
            //         'created_at' => $dateTime,
            //         'message' => 'Order #' . $order->id . ' has been picked up by customer successfully'
            //     ],
            //     [
            //         'order_id' => $order->id,
            //         'sender_id' => null,
            //         'receiver_id' => $order->user_id,
            //         'action_type' => 'Order Picked Up',
            //         'created_at' => $dateTime,
            //         'message' => 'You have successfully confirmed the picked up order #' . $order->id
            //     ]
            // ];
            // $this->sendNotification($data);

            return redirect()->back()->with('success', 'Picked up confirmed successfully');
        }

        return redirect()->route('vieworder', [$order->id]);
    }

    public function orderReturn(OrderPickUpReturnRequest $request, Order $order)
    {
        if ('Yes' == $order->dispute_status || 'Resolved' == $order->dispute_status) {
            return redirect()->back()->with("warning", "You can not upload the image of disputed order");
        }

        if ($order->status != "Picked Up") {
            return redirect()->back()->with("warning", 'Order must be picked up before upload the returned images');
        }

        if ($order->retailer_confirmed_returned == 0) {
            $userId = auth()->user()->id;
            // $removedImageIds = explode(',', $request->removed_images);
            $images = [];
            // for ($i = 1; $i <= $request->global_max_returned_image_count; $i++) {
            //     if ($request->hasFile('image' . $i)) {
            //         $image = s3_store_image($request->file('image' . $i), 'products/images');
            //         if ($image != null) {
            //             $images[] = [
            //                 'order_id' => $order->id,
            //                 'user_id' => $userId,
            //                 'url' => $image['url'],
            //                 'file' => $image['name'],
            //                 'type' => 'returned',
            //                 'uploaded_by' => 'customer',
            //             ];
            //         }
            foreach ($request->images as $file) {
                // dd($request);
                // if ($request->hasFile('image' . $i)) {
                //     $image = s3_store_image($request->file('image' . $i), 'products/images');
                if ($file != null) {
                    $images[] = [
                        'order_id' => $order->id,
                        'user_id' => $userId,
                        'url' => Storage::disk('public')->put('orders/return', $file),
                        'file' => $file->getClientOriginalName(),
                        'type' => 'returned',
                        'uploaded_by' => 'customer',
                    ];
                }

                // $file = $request->file('image'.$i);
                // $path = $file->store('orders/return', 's3');
                // $url = Storage::disk('s3')->url($path);
                // $images[] = [
                //     'order_id' => $order->id,
                //     'user_id' => $userId,
                //     'url' => $url,
                //     'file' => $path,
                //     'type' => 'returned',
                //     'uploaded_by' => 'customer',
                // ];
            }
        }

        // if (count($images) || count($removedImageIds)) {
        //     $orderImages = OrderImage::where('order_id', $order->id)->whereIn('id', $removedImageIds)->where('user_id', $userId)->where('type', 'returned')->where('uploaded_by', 'customer')->get();
        //     foreach ($orderImages as $orderImage) {
        //         Storage::disk('s3')->delete('products/images/' . $orderImage->file);
        //         // $orderImage->delete();
        //     }

        if (count($images)) {
            OrderImage::insert($images);
        }
        // }
        $customer_name = $order->user->name;
        $order->retailer->notify(new LenderImageUploadForReturn($customer_name , $order));
        return redirect()->back()->with('success', 'Images uploaded successfully');
        // }

        return redirect()->route('vieworder', [$order->id]);
    }

    // retailer confirm returned
    public function confirmReturn(Order $order)
    {
        if ('Yes' == $order->dispute_status || 'Resolved' == $order->dispute_status) {
            return redirect()->back()->with("warning", "You can not confirm return of the disputed order");
        }

        if ($order->status != "Picked Up") {
            return redirect()->back()->with("warning", 'Order must be picked up before confirm the returned');
        }
        // $order_item = OrderItem::where('order_id', $order->id)->first();
        // $product = Product::with('thumbnailImage', 'retailer.notification')->where('id', $order_item->product_id)->first();
        $image = OrderImage::where('order_id', $order->id)->where('type', 'returned')->where('uploaded_by', 'retailer')->first();
        $dateTime = date('Y-m-d H:i:s');

        $user = User::where('id', auth()->user()->id)->first();

        if (!is_null($image) && $order->customer_confirmed_returned == 0) {
            $data = [
                'customer_confirmed_returned' => '1',
            ];

            // check is customer confirmed the pickup then change order status to picked up
            if ($order->retailer_confirmed_returned == 1) {
                $data['status'] = 'Completed';
                $data['returned_date'] = $dateTime;
                Order::where("id", $order->id)->update(["status" => "Completed"]);
                $this->payToRetailer($order);

                $customer_info = [
                    'product_name'=>$order->product->name,
                    'order_id'=>$order->id,
                    'user_name' => $order->user->name,
                    'from_date' => $order->from_date,
                    'to_date' => $order->to_date,
                    'pickup_location' => $order->product->productCompleteLocation->pick_up_location,
                ];

                $lender_info = [
                    'product_name'=>$order->product->name,
                    'order_id'=>$order->id,
                    'lender_name' => $order->retailer->name,
                    'from_date' => $order->from_date,
                    'to_date' => $order->to_date,
                    'pickup_location' => $order->product->productCompleteLocation->pick_up_location,

                ];
                if (@$order->user->usernotification->customer_order_return == '1') {
                    $order->user->notify(new CutomerOrderReturn($customer_info));
                }
                if (@$order->retailer->usernotification->lender_order_return == '1') {
                    $order->retailer->notify(new LenderOrderReturn($lender_info));
                }
                // send mail to customer whe lender verify product
                // mail
                // $user->notify(new RefundCustomerSecuirty($user));
                // end

                // check the customer order return status before sending the notification
                // if (@$user->notification->order_return == 'on') {
                //     // send mail to retailer of order picked up successfully
                //     $user->notify(new OrderReturn($order));
                // }

                // check the retailer order return status before sending the notification
                // if (@$order->item->retailer->notification->order_return == 'on') {
                //     // send mail to retailer of order picked up successfully
                //     $order->item->retailer->notify(new VendorOrderReturn($order));
                // }
            }


            // if (@$user->notification->rate_your_experience == 'on') {
            //     $user->notify(new CustomerExperience($user, $product));
            // }
            // if (@$user->notification->order_return == 'on') {
            //     $user->notify(new RentalComplete($user));
            // }
            // if (@$user->notification->feedback == 'on') {
            //     $user->notify(new RentalFeedback($user));
            // }
            $order->update($data);
            // $data = [
            //     [
            //         'order_id' => $order->id,
            //         'sender_id' => $order->user_id,
            //         'receiver_id' => $order->item->retailer->id,
            //         'action_type' => 'Order Return',
            //         'created_at' => $dateTime,
            //         'message' => 'Order #' . $order->id . ' has been returned by customer successfully'
            //     ],
            //     [
            //         'order_id' => $order->id,
            //         'sender_id' => null,
            //         'receiver_id' => $order->user_id,
            //         'action_type' => 'Order Return',
            //         'created_at' => $dateTime,
            //         'message' => 'You have successfully returned the order #' . $order->id
            //     ]
            // ];
            // $this->sendNotification($data);


            return redirect()->back()->with('success', 'Return confirmed successfully');
        }

        return redirect()->route('vieworder', [$order->id]);
    }

    private function payToRetailer($order)
    {
        $order->load(["transaction", "retailer", "queryOf"]);
        $order_commission = AdminSetting::where('key', 'order_commission')->first();
        $identity_amount = AdminSetting::where('key', 'identity_commission')->pluck('value')->first();


        if (isset($order->queryOf->negotiate_price)) {
            if($order_commission->type=='Percentage'){

                $amount = $order->queryOf->negotiate_price * ($order_commission->value / 100);
                $dealerAmount = $order->total - $amount;
            }else{
                $amount = $order->queryOf->negotiate_price - ($order_commission->value);
                $dealerAmount = $order->total - $amount;
            }
        } else {
            $amount = $order->queryOf->getCalculatedPrice($order->queryOf->date_range) * ($order_commission->value / 100);
            $dealerAmount = $order->total - $amount;
        }
        if (isset(auth()->user()->identity_status) && auth()->user()->identity_status == 'unpaid') {
            $dealerAmount =  $dealerAmount - $identity_amount;
        }
        $payoutData = [
            "amount" => floatval($dealerAmount) * 100,
            "currency" => "usd",
            "destination" => $order->queryOf->forUser->stripe_account_id,
            "metadata" => [
                "order_ids" => $order->id
            ]
        ];
        $stripe = new Stripe\StripeClient(env('STRIPE_SECRET'));
        $transfer = $stripe->transfers->create([
            $payoutData
        ]);

        $retaileramount = RetailerPayout::create([
            "retailer_id" => $order->queryOf->forUser->id,
            "transaction_id" => $transfer['id'],
            "order_id" => $order->id,
            "amount" => $transfer['amount'] / 100,
            "gateway_response" => str_replace("Stripe\Transfer JSON: ", "", $transfer)

        ]);
        $user = User::find($order->queryOf->forUser->id);
        $user->update([
            'identity_status' => 'paid'
        ]);
        return true;
    }
    public function addReview(RatingRequest $request)
    {
        try {

            // $url = route('orders');
            $product_id = $request->product_id;

            // $chk_review = ProductRating::whereOrderId(1)->whereUserId(auth()->user()->id)->whereProductId($product_id)->exists();

            // $chk_review = ProductRating::whereUserId(auth()->user()->id)->whereProductId($product_id)->exists();
            $chk_review = ProductRating::whereUserId(auth()->user()->id)->whereIn('order_id', [$request->order_id])->exists();
            if ($chk_review) {
                return response()->json([
                    'success'    =>  false,
                    'messages'       =>   'Review already added'
                ], 200);
            }
            ProductRating::create([
                'order_id' => $request->order_id,
                'user_id' => auth()->user()->id,
                'product_id' => $product_id,
                'rating' => $request->rating,
                'review' => $request->review ?? null,
            ]);
            // dd('create');

            return response()->json([
                'success'    =>  true,
                'messages' => 'Review added successfully',
                // 'url'       =>   $url
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success'    =>  false,
            ]);
        }
    }

    public function downloadImage(Request $request, Order $order, Chat $chat)
    {
        $path = Storage::disk('s3')->url($chat->file);
        $mime = substr($path, strrpos($path, ".") + 1);
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . basename($path));
        header("Content-Type: ." . $mime);

        return readfile($path);
    }

    // public function cancelOrder(Request $request, Order $order)
    // {
    //     $order->load(["transaction", "retailer", "queryOf"]);
    //     $order_commission = AdminSetting::where('key', 'order_commission')->first();
    //     $url = route('orders', ['tab' => 'cancelled']);
    //     $product = Product::where('id', $order->product_id)->get();

    //     if ('Yes' == $order->dispute_status || 'Resolved' == $order->dispute_status) {
    //         session()->flash('warning', "You can not cancel the disputed order");
    //         return response()->json([
    //             'success'    =>  false,
    //             'url'       =>   $url
    //         ], 201);
    //     }
    //     if ($order->status != "Waiting") {
    //         session()->flash('warning', __("order.messages.cancel.notAllowed"));
    //         return response()->json([
    //             'success'    =>  false,
    //             'url'       =>   $url
    //         ], 201);
    //     }

    //     if (is_null($order->transaction->payment_id ?? '') || empty($order->transaction->payment_id ?? '')) {
    //         session()->flash('warning', __("order.messages.cancel.paymentIncomplete"));
    //         return response()->json([
    //             'success'    =>  false,
    //             'url'       =>   $url
    //         ], 201);
    //     }
    //     if ($order->cancellation_time_left < 1) {
    //         session()->flash('warning', 'Your cancellation time period has gone');
    //         return response()->json([
    //             'success'    =>  false,
    //             'url'       =>   $url
    //         ], 201);
    //     }

    //     // if ($order->cancellation_time_left <= 2) {
    //     //     session()->flash('warning', 'Your cancellation time period has gone');
    //     //     return response()->json([
    //     //         'success'    =>  false,
    //     //         'url'       =>   $url
    //     //     ], 201);
    //     // }

    //     $stripe = new Stripe\StripeClient(env('STRIPE_SECRET'));
    //     /*RETRIEVE PAYMENT INTENT DETAILS*/
    //     $paymentIntentData = $stripe->paymentIntents->retrieve(
    //         $order->transaction->payment_id
    //     );
    //     if (!isset($paymentIntentData->latest_charge)) {
    //         session()->flash('error', __("order.messages.cancel.paymentIncomplete"));
    //         return response()->json([
    //             'success'    =>  false,
    //             'url'       =>   $url
    //         ], 201);
    //     }
    //     /*REFUND PAYMENT*/
    //     // dd($paymentIntentData->latest_charge);


    //     if (isset($order->queryOf->negotiate_price)) {
    //         $amount = $order->queryOf->negotiate_price * ($order_commission->value / 100);
    //         $customerAmount = $order->total - $amount;
    //     } else {
    //         $amount = $order->queryOf->getCalculatedPrice($order->queryOf->date_range) * ($order_commission->value / 100);
    //         $customerAmount = $order->total - $amount;
    //     }

    //     try {

    //         if ($product->cancellation_policy == 'flexible') {
    //             $now = Carbon::now();
    //             $date = $order->from_date;

    //             $differenceInDays = $now->diffInDays(Carbon::parse($date));

    //             if ($differenceInDays > 7) {
    //                 $refundStatus = $stripe->refunds->create([
    //                     'charge' => $paymentIntentData->latest_charge,
    //                     'amount' => floatval($customerAmount) * 100, // Full amount
    //                 ]);
    //             } else {
    //                 $refundStatus = $stripe->refunds->create([
    //                     'charge' => $paymentIntentData->latest_charge,
    //                     'amount' => floatval($customerAmount / 2) * 100, // 50% amount
    //                 ]);
    //             }
    //         }

    //         if ($product->cancellation_policy == 'firm') {
    //             $now = Carbon::now();
    //             $date = $order->from_date;

    //             $differenceInDays = $now->diffInDays(Carbon::parse($date));

    //             if ($differenceInDays >= 30) {
    //                 $refundStatus = $stripe->refunds->create([
    //                     'charge' => $paymentIntentData->latest_charge,
    //                     'amount' => floatval($customerAmount) * 100, // Full amount
    //                 ]);
    //             } elseif ($differenceInDays > 7 && $differenceInDays < 30) {
    //                 $refundStatus = $stripe->refunds->create([
    //                     'charge' => $paymentIntentData->latest_charge,
    //                     'amount' => floatval($customerAmount / 2) * 100, // 50% amount
    //                 ]);
    //             }
    //             else{

    //             }
    //         }




    //         // $refundStatus = $stripe->refunds->create(
    //         //     [
    //         //         'charge' => $paymentIntentData->latest_charge,
    //         //         'amount' => ($order->cancellation_time_left >= 2) ? floatval($customerAmount) * 100 : floatval($customerAmount / 2) * 100,
    //         //     ],

    //         // );
    //     } catch (Exception $e) {
    //         session()->flash('error', str_replace("Charge " . $paymentIntentData->latest_charge, "Order ", $e->getMessage()));
    //         return response()->json([
    //             'success'    =>  false,
    //             'url'       =>   $url
    //         ], 201);
    //     }



    //     if ($refundStatus->status == "succeeded") {
    //         $updateData = [
    //             "status" => "Cancelled"
    //         ];
    //         $dateTime = date('Y-m-d H:i:s');
    //         /*UPDATE ORDER*/
    //         Order::where("id", $order->id)->update([
    //             "status" => "Cancelled",
    //             "cancelled_date" => $dateTime,
    //             'cancellation_note' => $request->cancellation_note
    //         ]);

    //         /*UPDATE ORDER ITEMS*/
    //         // OrderItem::where("order_id", $order->id)->update($updateData);

    //         $order->transaction->update($updateData);
    //         /*UPDATE TRANSACTION*/
    //         // Transaction::where("order_id", $order->id)->update($updateData);

    //         /*REMOVE PRODUCT UNAVAILABLE DATES*/

    //         ProductDisableDate::where('product_id', $order->product_id)->where("disable_date", '>=', $order->from_date)->where("disable_date", '<=', $order->to_date)->delete();

    //         $user = auth()->user();
    //         $ordernotificaton = Order::with('retailer.usernotification')->where('retailer_id', $order->retailer_id)->first();
    //         // // check the retailer order cancelled status before sending the notification
    //         if (isset($order->retailer->usernotification) && $order->retailer->usernotification->order_canceled_by_customer == 1) {
    //             $order->retailer->notify(new RentalCancelorder());
    //         }

    //         session()->flash('success', __("order.messages.cancel.success"));
    //         return response()->json([
    //             'success'    =>  true,
    //             'url'       =>   $url
    //         ], 200);
    //     }

    //     session()->flash('error', __("order.messages.cancel.error"));
    //     return response()->json([
    //         'success'    =>  false,
    //         'url'       =>   $url
    //     ], 201);
    // }



    public function cancelOrder(Request $request, Order $order)
    {
        $order->load(["transaction", "retailer", "queryOf"]);
        $order_commission = AdminSetting::where('key', 'order_commission')->first();
        $url = route('orders', ['tab' => 'cancelled']);
        $urlconditionfalse = route('retailercustomer', ['tab' => 'active']);

        $product = Product::where('id', $order->product_id)->first();

        if (in_array($order->dispute_status, ['Yes', 'Resolved'])) {
            session()->flash('warning', "You cannot cancel a disputed order.");
            return response()->json([
                'success' => false,
                'url' => $urlconditionfalse,
            ], 201);
        }

        if ($order->status != "Waiting") {
            session()->flash('warning', __("order.messages.cancel.notAllowed"));
            return response()->json([
                'success' => false,
                'url' => $urlconditionfalse,
            ], 201);
        }

        if (empty($order->transaction->payment_id)) {
            session()->flash('warning', __("order.messages.cancel.paymentIncomplete"));
            return response()->json([
                'success' => false,
                'url' => $urlconditionfalse,
            ], 201);
        }

        if ($order->cancellation_time_left < 1) {
            session()->flash('warning', 'Your cancellation time period has passed.');
            return response()->json([
                'success' => false,
                'url' => $urlconditionfalse,
            ], 201);
        }

        $stripe = new Stripe\StripeClient(env('STRIPE_SECRET'));
        $paymentIntentData = $stripe->paymentIntents->retrieve($order->transaction->payment_id);
        $charge = $stripe->charges->retrieve($paymentIntentData->latest_charge);

        // Calculate the remaining unrefunded amount
        $remainingRefundableAmount = $charge->amount - $charge->amount_refunded;
        
        if (!isset($paymentIntentData->latest_charge)) {
            session()->flash('error', __("order.messages.cancel.paymentIncomplete"));
            return response()->json([
                'success' => false,
                'url' => $url,
            ], 201);
        }
        // Calculate refund amounts
        if (isset($order->queryOf->negotiate_price)) {
            $amount = $order->queryOf->negotiate_price * ($order_commission->value / 100);
            $customerAmount = $order->total - $amount;
        } else {
            $amount = $order->queryOf->getCalculatedPrice($order->queryOf->date_range) * ($order_commission->value / 100);
            $customerAmount = $order->total - $amount;
        }
        try {
            $refundAmount = 0;
            $refundType = 'none';
            $now = Carbon::now();
            $date = $order->from_date;
            $differenceInDays = $now->diffInDays(Carbon::parse($date));
            // Refund logic based on cancellation policy

            if ($product->cancellation_policy == 'flexible') {
                if ($differenceInDays > 7) {
                    $refundAmount = min((int)(floatval($customerAmount) * 100), $remainingRefundableAmount);
                   
                    if ($refundAmount > 0) {
                        $refundStatus = $stripe->refunds->create([
                            'charge' => $paymentIntentData->latest_charge,
                        ]);
                        $refundAmount = $customerAmount;
                        $refundType = 'flexible';
                    } else {
                        session()->flash('warning', 'No remaining refundable amount for this charge.');
                    }
            
                } elseif ($differenceInDays <= 7 && $differenceInDays > 0) {
                    $refundAmount = min((floatval($customerAmount / 2) * 100), $remainingRefundableAmount);
                   
                    if ($refundAmount > 0) {
                        $refundStatus = $stripe->refunds->create([
                            'charge' => $paymentIntentData->latest_charge,
                            'amount' => $refundAmount,
                        ]);
                        $refundAmount = $customerAmount / 2;
                        $refundType = 'flexible';
                    } else {
                        session()->flash('warning', 'No remaining refundable amount for this charge.');
                    }
                } else {
                    session()->flash('warning', 'The order has been canceled, but no refund will be initiated since cancellation is too close to the booking date.');
                }
            }elseif ($product->cancellation_policy == 'firm') {
                if ($differenceInDays >= 30) {
                    $refundAmount = min((int)(floatval($customerAmount) * 100), $remainingRefundableAmount);
                    if ($refundAmount > 0) {
                        $refundStatus = $stripe->refunds->create([
                            'charge' => $paymentIntentData->latest_charge,
                        ]);
                        $refundAmount = $customerAmount;
                        $refundType = 'firm';
                    } else {
                        session()->flash('warning', 'No remaining refundable amount for this charge.');
                    }
            
                } elseif ($differenceInDays > 7 && $differenceInDays < 30) {
                    $refundAmount = min((int)(floatval($customerAmount / 2) * 100), $remainingRefundableAmount);
                   
                    if ($refundAmount > 0) {
                        $refundStatus = $stripe->refunds->create([
                            'charge' => $paymentIntentData->latest_charge,
                            'amount' => $refundAmount,
                        ]);
                        $refundAmount = $customerAmount / 2;
                        $refundType = 'firm';
                    } else {
                        session()->flash('warning', 'No remaining refundable amount for this charge.');
                    }
            
                } else {
                    session()->flash('warning', 'The order has been canceled, but no refund will be initiated due to late cancellation.');
                }
            }
            
        
            // Store refund details in refunds table
            Refund::create([
                'orderId' => $order->id,
                'refund_amount' => $refundAmount,
                'canceled_by' => auth()->id(),
                'refund_type' => $refundType,
            ]);
        } catch (Exception $e) {
            session()->flash('error', str_replace("Charge " . $paymentIntentData->latest_charge, "Order ", $e->getMessage()));
            return response()->json([
                'success' => false,
                'url' => $url,
            ], 201);
        }

        Order::where("id", $order->id)->update([
            "status" => "Cancelled",
            "cancelled_date" => now(),
            'cancellation_note' => $request->cancellation_note,
        ]);

        ProductDisableDate::where('product_id', $order->product_id)
            ->where("disable_date", '>=', $order->from_date)
            ->where("disable_date", '<=', $order->to_date)
            ->delete();

        if (isset($order->retailer->usernotification) && $order->retailer->usernotification->order_canceled_by_customer == 1) {
            // $order->retailer->notify(new RentalCancelorder());
        }

        session()->flash('success', __("order.messages.cancel.success"));
        return response()->json([
            'success' => true,
            'url' => $url,
        ], 200);
    }



    // dispute
    public function orderDispute(DisputeRequest $request, Order $order)
    {
        if (in_array($order->status, ['Completed', 'Cancelled'])) {
            return redirect()->back()->with("warning", "You can not raise a dispute for cancelled and completed orders");
        }

        if ('Yes' == $order->dispute_status || 'Resolved' == $order->dispute_status) {
            return redirect()->back()->with("warning", "You can not raise a dispute of already disputed order");
        }

        $userId = auth()->user()->id;
        $dateTime = date('Y-m-d H:i:s');
        $images = [];
        // for ($i = 1; $i <= $request->global_max_dispute_image_count; $i++) {
        //     // if ($request->hasFile('dispute_image' . $i)) {
        //         // $image = s3_store_image($request->file('dispute_image' . $i), 'products/images');
        //         if ($image != null) {
        //             $images[] = [
        //                 'order_id' => $order->id,
        //                 'user_id' => $userId,
        //                 'url' => $image['url'],
        //                 'file' => $image['name'],
        //                 'type' => 'disputed',
        //                 'uploaded_by' => 'customer',
        //             ];
        //         }

        if (isset($request->images)) {

            foreach ($request->images as $file) {
                // dd($request);
                // if ($request->hasFile('image' . $i)) {
                //     $image = s3_store_image($request->file('image' . $i), 'products/images');
                if ($file != null) {
                    $images[] = [
                        'order_id' => $order->id,
                        'user_id' => $userId,
                        'url' => Storage::disk('public')->put('orders/dispute', $file),
                        'file' => $file->getClientOriginalName(),
                        'type' => 'disputed',
                        'uploaded_by' => 'customer',
                    ];
                }
            }
        }

        // $file = $request->file('dispute_image'.$i);
        // $path = $file->store('orders/dispute', 's3');
        // $url = Storage::disk('s3')->url($path);
        // $images[] = [
        //     'order_id' => $order->id,
        //     'user_id' => $userId,
        //     'url' => $url,
        //     'file' => $path,
        //     'type' => 'disputed',
        //     'uploaded_by' => 'customer',
        // ];
        // }
        // }

        if (count($images)) {
            DisputeOrder::create([
                'subject' => $request->subject,
                'description' => $request->description,
                'order_id' => $order->id,
                'reported_id' => $userId,
                'reported_by' => 'customer',
            ]);
            OrderImage::insert($images);
            $disputeData = [
                'dispute_status' => 'Yes',
                'dispute_date' => $dateTime

            ];

            // OrderItem::where('order_id', $order->id)->update($disputeData);
            $order->update($disputeData);
        }

        // $data = [
        //     [
        //         'order_id' => $order->id,
        //         'sender_id' => $order->user_id,
        //         'receiver_id' => $order->item->retailer->id,
        //         'action_type' => 'Order Dispute',
        //         'created_at' => $dateTime,
        //         'message' => 'A dispute has been raised on Order #' . $order->id . ' by customer'
        //     ],
        //     [
        //         'order_id' => $order->id,
        //         'sender_id' => null,
        //         'receiver_id' => $order->user_id,
        //         'action_type' => 'Order Dispute',
        //         'created_at' => $dateTime,
        //         'message' => 'You have reported a dispute for the order #' . $order->id
        //     ]
        // ];
        // $this->sendNotification($data);

        return redirect()->route('orders', ['tab' => 'dispute'])->with('success', 'Your dispute submitted successfully. We will contact you soon');
    }

    // download order attachments
    public function downloadAttachment(Order $order, OrderImage $image)
    {
        $path = Storage::disk('s3')->url('products/images/' . $image->file);
        $mime = substr($path, strrpos($path, ".") + 1);
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . basename($path));
        header("Content-Type: ." . $mime);

        return readfile($path);
    }

    // rental request
    public function rental_request(Request $request)
    {

        return view('customer.rental_request');
    }
}
