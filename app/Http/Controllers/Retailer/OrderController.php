<?php

namespace App\Http\Controllers\Retailer;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChatRequest;
use App\Http\Requests\DisputeRequest;
use App\Http\Requests\OrderPickUpReturnRequest;
use App\Http\Requests\RatingRequest;
use App\Models\ProductUnavailability;
use App\Models\Transaction;
use App\Traits\SmsTrait;
use Stripe, Exception, DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\{AdminSetting, BillingToken, Order, Chat, CustomerBillingDetails, CustomerRating, DisputeOrder, OrderImage, OrderItem, Product, User, RetailerPayout, ProductDisableDate, Refund};
use App\Notifications\{CustomerImageUpload, CustomerImageUploadForReturn, CustomerOrderPickup, CutomerOrderReturn, LenderOrderPickup, LenderOrderReturn, OrderPickUp, OrderReturn, VendorOrderPickedUp, VendorOrderReturn, RateYourExperience, VendorOrderCancelled};


class OrderController extends Controller
{
    use SmsTrait;
    // use ProductTrait;
    // list all orders of the retailer
    public function __construct()
    {
        // dd(env('STRIPE_SECRET'));
        $this->stripe = Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $this->stripeClient = new Stripe\StripeClient(env('STRIPE_SECRET'));
    }

    public function index(Request $request)
    {

        $fromDate = $toDate = null;
        $fromAndToDate = array_map('trim', explode($request->global_date_separator, $request->date));
        if (count($fromAndToDate) == 2) {
            $fromDate = DateTime::createFromFormat($request->global_date_format, $fromAndToDate[0])->format('Y-m-d');
            $toDate = DateTime::createFromFormat($request->global_date_format, $fromAndToDate[1])->format('Y-m-d');
        }

        $orders = Order::with('product.thumbnailImage', 'product.retailer', 'product.category', 'transaction', 'customerquery', 'product.nonAvailableDates')
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
            ->where('retailer_id', auth()->user()->id)
            ->orderByDesc('id')
            ->paginate($request->global_pagination);


        // return view('retailer.order_list', compact('orderItems', 'waitingCount', 'pickedUpCount', 'completedCount', 'cancelledCount', 'disputedCount'));
        return view('retailer.order_history', compact('orders'));
    }

    public function viewOrder(Order $order)
    {
        $order->load(['user', 'retailer', 'rating', 'customerRating', 'location', 'product.thumbnailImage', 'transaction', 'customerPickedUpImages', 'customerReturnedImages', 'retailerPickedUpImages', 'retailerReturnedImages', 'disputedOrderImages']);
        // $transaction = Transaction::where('order_id', $order->id)->first();
        $billing_token = BillingToken::where('order_id', $order->id)->first();
        // dd($order->toArray());

        return view('retailer.order_view', compact('order', 'billing_token'));
        // return view('retailer.order_detail', compact('order', 'billing_token'));
    }


    public function orderChat(Order $order)
    {
        //$chats = Chat::with('customer')->chat($order->id)->get();

        $chatlist = OrderItem::with(['product', 'chat'])->whereRetailerId(auth()->user()->id)->get();
        // dd($chatlist->toArray());

        return view('retailer.order_chat', compact('order', 'chatlist'));
    }


    public function saveChat(ChatRequest $request, Order $order)
    {
        $userId = auth()->user()->id;
        $data = [
            'message' => $request->message,
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'retailer_id' => $userId,
            'sent_by' => 'Retailer',
        ];

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('chats', 's3');
            $url = Storage::disk('s3')->url($path);
            $data['file'] = $path;
            $data['url'] = $url;
        }

        $chat = Chat::create($data);
        $chatHtml = view('retailer.chat_message', compact('chat', 'order'))->render();

        return response()->json(['title' => __('order.success'), 'data' => $chatHtml, 'message' => __('order.messages.success')]);
    }

    public function orderPickUp(OrderPickUpReturnRequest $request, Order $order)
    {
        $order->load('retailer', 'user', 'product');
        if ('Yes' == $order->dispute_status || 'Resolved' == $order->dispute_status) {
            return redirect()->back()->with("warning", "You can not allot dispute order to customer");
        }

        if ($order->status != "Waiting") {
            return redirect()->back()->with("warning", 'Order must be in waiting state to upload the images.');
        }

        if ($order->customer_confirmed_pickedup == 0) {
            $userId = auth()->user()->id;
            // $removedImageIds = explode(',', $request->removed_images);



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
                        'uploaded_by' => 'retailer',
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
                //     'uploaded_by' => 'retailer',
                // ];
                // }
            }
            // if (count($images)) {
            // $orderImages = OrderImage::where('order_id', $order->id)->whereIn('id', $removedImageIds)->where('user_id', $userId)->where('type', 'pickedup')->where('uploaded_by', 'retailer')->get();
            // foreach ($orderImages as $orderImage) {
            //     Storage::disk('s3')->delete('products/images/' . $orderImage->file);
            //     // $orderImage->delete();
            // }

            if (count($images)) {
                OrderImage::insert($images);
            }
            // }
            $lender_name = $order->retailer->name;
            $order->user->notify(new CustomerImageUpload($lender_name, $order));

            return redirect()->back()->with('success', 'Images uploaded successfully');
        }

        return redirect()->route('retailer.vieworder', [$order->id]);
    }

    // retailer confirm pickup
    public function confirmPickUp(Order $order)
    {
        if ('Yes' == $order->dispute_status || 'Resolved' == $order->dispute_status) {
            return redirect()->back()->with("warning", "You can not confirm the dispute order pickup");
        }

        if ($order->status != "Waiting") {
            return redirect()->back()->with("warning", 'Order must be in waiting state to confirm the pick up');
        }

        $image = OrderImage::where('order_id', $order->id)->where('type', 'pickedup')->where('uploaded_by', 'customer')->first();
        if (!is_null($image) && $order->retailer_confirmed_pickedup == '0') {
            $dateTime = date('Y-m-d H:i:s');
            $data = [
                'retailer_confirmed_pickedup' => '1'
            ];

            // check is customer confirmed the pickup then change order status to picked up
            if ($order->customer_confirmed_pickedup == 1) {
                $data['status'] = 'Picked Up';
                $data['pickedup_date'] = $dateTime;
                Order::where("id", $order->id)->update(["status" => "Picked Up"]);
                $user = auth()->user();

                // check the retailer order pickup status before sending the notification
                $customer_info = [
                    'user_name' => $order->user->name,
                    'from_date' => $order->from_date,
                    'to_date' => $order->to_date,
                    'pickup_location' => $order->product->productCompleteLocation->pick_up_location,
                ];

                $lender_info = [
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
            //         'sender_id' => $order->item->retailer->id,
            //         'receiver_id' => $order->user_id,
            //         'action_type' => 'Order Picked Up',
            //         'created_at' => $dateTime,
            //         'message' => 'Order #' . $order->id . ' has been picked up by you successfully'
            //     ],
            //     [
            //         'order_id' => $order->id,
            //         'sender_id' => null,
            //         'receiver_id' => $order->item->retailer->id,
            //         'action_type' => 'Order Picked Up',
            //         'created_at' => $dateTime,
            //         'message' => 'You have successfully confirmed the picked up order #' . $order->id
            //     ]
            // ];
            // $this->sendNotification($data);

            return redirect()->back()->with('success', 'Pick up confirmed successfully');
        }

        return redirect()->route('retailer.vieworder', [$order->id]);
    }


    public function orderReturn(OrderPickUpReturnRequest $request, Order $order)
    {
        if ('Yes' == $order->dispute_status || 'Resolved' == $order->dispute_status) {
            return redirect()->back()->with("warning", "You can not upload the image of disputed order");
        }

        if ($order->status != "Picked Up") {
            return redirect()->back()->with("warning", 'Order must be picked up before upload the returned images');
        }

        if ($order->customer_confirmed_returned == 0) {
            $userId = auth()->user()->id;
            $removedImageIds = explode(',', $request->removed_images);
            // $images = [];
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
            //                 'uploaded_by' => 'retailer',
            //             ];
            //         }

            $images = [];
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
                        'uploaded_by' => 'retailer',
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
                //     'uploaded_by' => 'retailer',
                // ];
            }
        }

        // if (count($images) || count($removedImageIds)) {
        //     $orderImages = OrderImage::where('order_id', $order->id)->whereIn('id', $removedImageIds)->where('user_id', $userId)->where('type', 'returned')->where('uploaded_by', 'retailer')->get();
        //     foreach ($orderImages as $orderImage) {
        //         Storage::disk('s3')->delete('products/images/' . $orderImage->file);
        //         // $orderImage->delete();
        //     }

        if (count($images)) {
            OrderImage::insert($images);
        }
        $lender_name = $order->retailer->name;
        $order->user->notify(new CustomerImageUploadForReturn($lender_name, $order));
        return redirect()->back()->with('success', 'Images uploaded successfully');
        // }
        // }

        return redirect()->route('retailer.vieworder', [$order->id]);
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

        $image = OrderImage::where('order_id', $order->id)->where('type', 'returned')->where('uploaded_by', 'customer')->first();
        $dateTime = date('Y-m-d H:i:s');
        if (!is_null($image) && $order->retailer_confirmed_returned == 0) {
            $data = [
                'retailer_confirmed_returned' => '1'
            ];
            $user = User::where('id', auth()->user()->id)->first();
            // check is customer confirmed the pickup then change order status to picked up
            if ($order->customer_confirmed_returned == 1) {
                $data['status'] = 'Completed';
                $data['returned_date'] = $dateTime;
                Order::where("id", $order->id)->update(["status" => "Completed"]);
                $this->payToRetailer($order);

                $customer_info = [
                    'user_name' => $order->user->name,
                    'from_date' => $order->from_date,
                    'to_date' => $order->to_date,
                    'pickup_location' => $order->product->productCompleteLocation->pick_up_location,
                ];

                $lender_info = [
                    'lender_name' => $order->retailer->name,
                    'from_date' => $order->from_date,
                    'to_date' => $order->to_date,
                    'pickup_location' => $order->product->productCompleteLocation->pick_up_location,

                ];

                if (@$order->user->usernotification->customer_order_return == '1') {
                    @$order->user->notify(new CutomerOrderReturn($customer_info));
                }
                if (@$order->retailer->usernotification->lender_order_return == '1') {
                    @$order->retailer->notify(new LenderOrderReturn($lender_info));
                }
                // check the retailer order return status before sending the notification
                // if (@$user->notification->order_return == 'on') {
                //     // send mail to retailer of order picked up successfully
                //     $user->notify(new VendorOrderReturn($order));
                // }

                // $orderitem = OrderItem::where("order_id", $order->id)->first();
                // $product = Product::where("id", $orderitem->product_id)->first();
                // $customer =  User::where('id', $orderitem->customer_id)->first();

                // $emaildata = [
                //     'orderitem' => $orderitem,
                //     'product' => $product,
                //     'customer' => $customer,
                // ];
                // if (@$user->notification->rate_your_experience == "on") {
                //     // rate your experience
                //     $user->notify(new RateYourExperience($emaildata));
                // }

                // // check the customer order return status before sending the notification
                // if (@$order->user->notification->order_return == 'on') {
                //     // send mail to retailer of order picked up successfully
                //     $order->user->notify(new OrderReturn($order));
                // }
            }
            $order->update($data);
            // $data = [
            //     [
            //         'order_id' => $order->id,
            //         'sender_id' => $order->item->retailer->id,
            //         'receiver_id' => $order->user_id,
            //         'action_type' => 'Order Return',
            //         'created_at' => date('Y-m-d H:i:s'),
            //         'message' => 'You have successfully returned the order #' . $order->id
            //     ],
            //     [
            //         'order_id' => $order->id,
            //         'sender_id' => null,
            //         'receiver_id' => $order->item->retailer->id,
            //         'action_type' => 'Order Return',
            //         'created_at' => date('Y-m-d H:i:s'),
            //         'message' => 'Order #' . $order->id . ' has been successfully returned the customer'
            //     ]
            // ];


            // $this->sendNotification($data);

            return redirect()->back()->with('success', 'Return confirmed successfully');
        }

        return redirect()->route('retailer.vieworder', [$order->id]);
    }
    private function payToRetailer($order)
    {
        $order->load(["transaction", "retailer", "queryOf"]);
        $order_commission = AdminSetting::where('key', 'order_commission')->first();
        $identity_amount = AdminSetting::where('key', 'identity_commission')->pluck('value')->first();

        if (isset($order->queryOf->negotiate_price)) {
            $amount = $order->queryOf->negotiate_price * ($order_commission->value / 100);
            $dealerAmount = $order->total - $amount;
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

        $user = auth()->user();
        $user->update([
            'identity_status' => 'paid'
        ]);
        return true;
    }
    // store customer rating
    public function addReview(RatingRequest $request, Order $order)
    {
        CustomerRating::updateOrCreate([
            'order_id' => $order->id,
            'retailer_id' => auth()->user()->id,
        ], [
            'customer_id' => $order->user_id,
            'product_id' => $order->item->product_id,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return redirect()->back()->with('success', 'Review added successfully');
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
        //     if ($request->hasFile('dispute_image' . $i)) {
        //         $file = $request->file('dispute_image' . $i);
        //         $path = $file->store('orders/dispute', 's3');
        //         $url = Storage::disk('s3')->url($path);

        //         $images[] = [
        //             'order_id' => $order->id,
        //             'user_id' => $userId,
        //             'url' => $url,
        //             'file' => $path,
        //             'type' => 'disputed',
        //             'uploaded_by' => 'retailer',
        //         ];
        //     }
        // }

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
                    'uploaded_by' => 'retailer',
                ];
            }
        }
        if (count($images)) {
            DisputeOrder::create([
                'subject' => $request->subject,
                'description' => $request->description,
                'order_id' => $order->id,
                'reported_id' => $userId,
                'reported_by' => 'retailer',
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
        //         'sender_id' => $userId,
        //         'receiver_id' => $order->user_id,
        //         'action_type' => 'Order Dispute',
        //         'created_at' => $dateTime,
        //         'message' => 'A dispute has been raised on Order #' . $order->id . ' by retailer'
        //     ],
        //     [
        //         'order_id' => $order->id,
        //         'sender_id' => null,
        //         'receiver_id' => $userId,
        //         'action_type' => 'Order Dispute',
        //         'created_at' => $dateTime,
        //         'message' => 'You have reported a dispute for the order #' . $order->id
        //     ]
        // ];
        // $this->sendNotification($data);

        return redirect()->route('retailercustomer', ['tab' => 'dispute'])->with('success', 'Your dispute submitted successfully. We will contact you soon');
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


    public function cancelOrder(Request $request, Order $order)
    {
        $order->load(["transaction"]);
        $url = route('retailercustomer', ['tab' => 'cancelled']);

        // Check if the order is disputed
        if (in_array($order->dispute_status, ['Yes', 'Resolved'])) {
            session()->flash('warning', "You cannot cancel a disputed order.");
            return response()->json(['success' => false, 'url' => $url], 201);
        }

        // Check if the order is in the correct status to be cancelled
        if ($order->status != "Waiting") {
            session()->flash('warning', __("order.messages.cancel.notAllowed"));
            return response()->json(['success' => false, 'url' => $url], 201);
        }

        // Check if payment was made
        if (is_null($order->transaction->payment_id)) {
            session()->flash('warning', __("order.messages.cancel.paymentIncomplete"));
            return response()->json(['success' => false, 'url' => $url], 201);
        }

        // Check if cancellation time has passed
        if ($order->cancellation_time_left < 1) {
            session()->flash('warning', 'Your cancellation time period has passed.');
            return response()->json(['success' => false, 'url' => $url], 201);
        }

        // Stripe initialization
        $stripe = new Stripe\StripeClient(env('STRIPE_SECRET'));

        try {
            // Retrieve payment intent details
            $paymentIntentData = $stripe->paymentIntents->retrieve($order->transaction->payment_id);
            $charge = $stripe->charges->retrieve($paymentIntentData->latest_charge);
            $remainingRefundableAmount = $charge->amount - $charge->amount_refunded;
            // dd($charge ,$charge->amount , $charge->amount_refunded);
        } catch (\Exception $e) {
            session()->flash('error', __("order.messages.cancel.paymentIncomplete"));
            return response()->json(['success' => false, 'url' => $url], 201);
        }

        if (!isset($paymentIntentData->latest_charge)) {
            session()->flash('error', __("order.messages.cancel.paymentIncomplete"));
            return response()->json(['success' => false, 'url' => $url], 201);
        }

        try {
            $refundAmount = min((int)(floatval($order->total) * 100), $remainingRefundableAmount);
            // dd($refundAmount);
            if($refundAmount > 0){
                $refundStatus = $stripe->refunds->create([
                    'charge' => $paymentIntentData->latest_charge,
                    'amount' => $refundAmount, // Stripe works with cents and expects an integer
                ]);
            }


        } catch (Exception $e) {
            session()->flash('error', str_replace("Charge " . $paymentIntentData->latest_charge, "Order ", $e->getMessage()));
            return response()->json(['success' => false, 'url' => $url], 201);
        }

        if ($refundStatus->status == "succeeded") {
            $dateTime = now();

            // Update the order status and cancellation details
            $order->update([
                "status" => "Cancelled",
                "cancelled_date" => $dateTime,
                'cancellation_note' => $request->cancellation_note
            ]);

            // Record the refund
            Refund::create([
                'orderId' => $order->id,
                'refund_amount' => $order->total,
                'canceled_by' => auth()->id(),
                'refund_type' => 'complete',
            ]);

            // Remove product disable dates for this order
            ProductDisableDate::where('product_id', $order->product_id)
                ->whereBetween('disable_date', [$order->from_date, $order->to_date])
                ->delete();

            // Notify the retailer
            if (isset($order->user->usernotification) && $order->user->usernotification->order_canceled_by_lender == 1) {
                $order->user->notify(new VendorOrderCancelled($order));

                // Send SMS notification
                $phoneNumber = $order->user->country_code . $order->user->phone_number;
                $otpMessage = [
                    'message' => 'Your order cancelled by lender for product ' . $order->product->name,
                    'url' => route('retailercustomer'),
                ];
                $this->sendSms($phoneNumber, $otpMessage);
            }

            session()->flash('success', __("order.messages.cancel.success"));
            return response()->json(['success' => true, 'url' => $url], 200);
        }

        session()->flash('error', __("order.messages.cancel.error"));
        return response()->json(['success' => false, 'url' => $url], 201);
    }
}
