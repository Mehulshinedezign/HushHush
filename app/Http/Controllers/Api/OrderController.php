<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DisputeRequest;
use App\Models\AdminSetting;
use App\Models\DisputeOrder;
use App\Models\Order;
use App\Models\OrderImage;
use App\Models\Product;
use App\Models\ProductDisableDate;
use App\Models\Query;
use App\Models\RetailerPayout;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class OrderController extends Controller
{

    public function uploadRetailerImages(Request $request, $id, $type)
    {
        try {
            // Log the entire request to see what is being sent from the frontend
            // Log::info('Received request for uploadRetailerImages', [
            //     'request_data' => $request->all(),
            //     'user_id' => auth()->id(),
            //     'order_id' => $id,
            //     'type' => $type,
            // ]);

            // Validate the uploaded images
            $request->validate([
                'images.*' => 'required|file|mimes:jpeg,png,jpg,gif,svg',
            ]);

            // Check for valid type
            if (!in_array($type, ['pickedup', 'returned'])) {
                // Log::warning('Invalid type provided', ['type' => $type]);
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid type provided',
                    'data' => [],
                ], 400);
            }

            $orderImages = [];

            // Check if files are present and handle the upload
            if ($request->hasFile('images')) {
                foreach ($request->images as $image) {
                    // Log each image file before storing
                    // Log::info('Processing image', [
                    //     'image_name' => $image->getClientOriginalName(),
                    //     'image_mime' => $image->getMimeType(),
                    // ]);

                    // Store the image
                    $path = $image->store('order_images', 'public');
                    $url = $path;

                    // Create the order image record
                    $orderImages[] = OrderImage::create([
                        'order_id' => $id,
                        'user_id' => auth()->id(),
                        'file' => $path,
                        'url' => $url,
                        'type' => $type,
                        'uploaded_by' => 'retailer',
                    ]);
                }

                if ($type == 'pickedup') {
                    $forUser = User::where('id', auth()->id())->with('usernotification', 'pushToken')->first();

                    if ($forUser && $forUser->usernotification && $forUser->usernotification->lender_order_pickup == '1') {
                        $payload['id'] = $id;
                        $payload['content'] = "Retailer Uploaded the image please verify";
                        $payload['role'] = 'borrower';
                        $payload['type'] = 'order';

                        if ($forUser->pushToken) {
                            sendPushNotifications($forUser->pushToken->fcm_token, $payload);
                        }
                    }
                }
                if ($type == 'returned') {
                    $forUser = User::where('id', auth()->id())->with('usernotification', 'pushToken')->first();

                    if ($forUser && $forUser->usernotification && $forUser->usernotification->lender_order_return == '1') {
                        $payload['id'] = $id;
                        $payload['content'] = "Retailer Uploaded the image please verify";
                        $payload['role'] = 'borrower';
                        $payload['type'] = 'order';

                        if ($forUser->pushToken) {
                            sendPushNotifications($forUser->pushToken->fcm_token, $payload);
                        }
                    }
                }

                // Log the success of the upload process
                // Log::info('Images uploaded successfully', [
                //     'order_id' => $id,
                //     'user_id' => auth()->id(),
                //     'uploaded_images' => $orderImages,
                // ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Images uploaded successfully',
                    'data' => $orderImages,
                ], 201);
            }

            // Log if no files were found in the request
            // Log::warning('No files found in the request', [
            //     'request_data' => $request->all(),
            // ]);

            return response()->json([
                'status' => false,
                'message' => 'Files not found',
                'data' => [],
            ], 400);
        } catch (\Throwable $e) {
            // Log the exception with stack trace
            // Log::error('Error occurred in uploadRetailerImages', [
            //     'error_message' => $e->getMessage(),
            //     'stack_trace' => $e->getTraceAsString(),
            // ]);

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => [
                    'errors' => [],
                ],
            ], 500);
        }
    }



    public function uploadCustomerImages(Request $request, $id, $type)
    {
        try {
            // dd($type);

            $request->validate([
                'images[].*' => 'required|file|mimes:jpeg,png,jpg,gif,svg',
            ]);

            $user = auth()->user();


            if (!in_array($type, ['pickedup', 'returned'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid type provided',
                    'data' => [],
                ], 400);
            }


            if ($request->hasFile('images')) {
                foreach ($request->images as $image) {
                    // dd($image);
                    $path = $image->store('order_images', 'public');
                    $url = $path;


                    $orderImages[] = OrderImage::create([
                        'order_id' => $id,
                        'user_id' => $user->id,
                        'file' => $path,
                        'url' => $url,
                        'type' => $type,
                        'uploaded_by' => 'customer',
                    ]);
                }

                if ($type == 'pickedup') {
                    $forUser = User::where('id', $user->id)->with('usernotification', 'pushToken')->first();

                    if ($forUser && $forUser->usernotification && $forUser->usernotification->customer_order_pickup == '1') {
                        $payload['id'] = $id;
                        $payload['content'] = "Lender Uploaded the image please verify";
                        $payload['role'] = 'lender';
                        $payload['type'] = 'order';

                        if ($forUser->pushToken) {
                            sendPushNotifications($forUser->pushToken->fcm_token, $payload);
                        }
                    }
                }
                if ($type == 'returned') {
                    $forUser = User::where('id', $user->id)->with('usernotification', 'pushToken')->first();

                    if ($forUser && $forUser->usernotification && $forUser->usernotification->customer_order_return == '1') {
                        $payload['id'] = $id;
                        $payload['content'] = "Lender Uploaded the image please verify";
                        $payload['role'] = 'lender';
                        $payload['type'] = 'order';

                        if ($forUser->pushToken) {
                            sendPushNotifications($forUser->pushToken->fcm_token, $payload);
                        }
                    }
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Images uploaded successfully',
                    'data' => $orderImages,
                ], 201);
            }


            return response()->json([
                'status' => false,
                'message' => 'File not found',
                'data' => [],
            ], 400);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => [
                    'errors' => [],
                ],
            ], 500);
        }
    }



    public function retailerVerifyImage(Request $request, $id, $type)
    {
        try {
            $user = auth()->user();

            if (!in_array($type, ['pickedup', 'returned'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid type provided',
                    'data' => [],
                ], 400);
            }

            $order = Order::find($id);

            if (!$order) {
                return response()->json([
                    'status' => false,
                    'message' => 'Order not found',
                    'data' => [],
                ], 404);
            }

            // Log::info("Before Update: ", $order->toArray());
            $dateTime = date('Y-m-d H:i:s');

            if ($type == 'pickedup') {
                $order->update(['retailer_confirmed_pickedup' => '1']);

                if ($order->customer_confirmed_pickedup == '1' && $order->status !== 'Picked Up') {
                    $order->update(['status' => 'Picked Up', 'pickedup_date' => $dateTime]);
                }
            }

            if ($type == 'returned') {
                $order->update(['retailer_confirmed_returned' => '1']);

                if ($order->customer_confirmed_returned == '1' && $order->status !== 'Completed') {
                    $order->update(['status' => 'Completed', 'returned_date' => $dateTime]);
                    dd('herer');
                    $this->payToRetailer($order);
                }
            }

            // Log::info("After Update: ", $order->toArray());

            return response()->json([
                'status' => true,
                'message' => 'Image verified successfully',
                'data' => $order,
            ], 200);
        } catch (\Throwable $e) {
            Log::error("Error: ", ['message' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => [
                    'errors' => [],
                ],
            ], 500);
        }
    }

    public function customerVerifyImage(Request $request, $id, $type)
    {
        try {
            $user = auth()->user();

            if (!in_array($type, ['pickedup', 'returned'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid type provided',
                    'data' => [],
                ], 400);
            }

            $order = Order::find($id);

            if (!$order) {
                return response()->json([
                    'status' => false,
                    'message' => 'Order not found',
                    'data' => [],
                ], 404);
            }

            // Log::info("Before Update: ", $order->toArray());
            $dateTime = date('Y-m-d H:i:s');

            if ($type == 'pickedup') {
                $order->update(['customer_confirmed_pickedup' => '1']);

                if ($order->retailer_confirmed_pickedup == '1' && $order->status !== 'Picked Up') {
                    $order->update(['status' => 'Picked Up', 'pickedup_date' => $dateTime]);
                }
            }

            if ($type == 'returned') {
                $order->update(['customer_confirmed_returned' => '1']);

                if ($order->retailer_confirmed_returned == '1' && $order->status !== 'Completed') {
                    $order->update(['status' => 'Completed',  'returned_date' => $dateTime]);
                    // dd('herer');
                    $this->payToRetailer($order);
                }
            }

            // Log::info("After Update: ", $order->toArray());

            return response()->json([
                'status' => true,
                'message' => 'Image verified successfully',
                'data' => $order,
            ], 200);
        } catch (\Throwable $e) {
            Log::error("Error: ", ['message' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => [
                    'errors' => [],
                ],
            ], 500);
        }
    }

    private function payToRetailer($order)
    {
        try {
            $order->load(["transaction", "retailer", "queryOf"]);

            $order_commission = AdminSetting::where('key', 'order_commission')->first();

            if ($order_commission->type === 'Percentage') {
                $amount = $order->total * ($order_commission->value / 100);
            } else if ($order_commission->type === 'Fixed') {
                $amount = $order_commission->value;
            }

            $retailerAmount = $order->total - $amount;

            $payoutData = [
                "amount" => floatval($retailerAmount) * 100, // Amount in cents
                "currency" => "usd",
                "destination" => $order->queryOf->forUser->bankAccount->stripe_id,
                "metadata" => [
                    "order_ids" => $order->id
                ]
            ];

            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            $transfer = $stripe->transfers->create($payoutData);



            $retailerPayout = RetailerPayout::create([
                "retailer_id" => $order->queryOf->forUser->id,
                "transaction_id" => $transfer['id'],
                "order_id" => $order->id,
                "amount" => $transfer['amount'] / 100,
                "gateway_response" => json_encode($transfer)
            ]);

            return true;
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => [
                    'errors' => [],
                ],
            ], 500);
        }
    }




    public function orderDisputeApi(DisputeRequest $request, Order $order)
    {
        try {
            $user = auth()->user();
            $userId = $user->id;
            // dd($order);

            if (in_array($order->status, ['Completed', 'Cancelled'])) {
                return response()->json([
                    'status' => false,
                    'message' => "You cannot raise a dispute for cancelled and completed orders",
                    'data' => [],
                ], 400);
            }

            if (in_array($order->dispute_status, ['Yes', 'Resolved'])) {
                return response()->json([
                    'status' => false,
                    'message' => "You cannot raise a dispute for an already disputed order",
                    'data' => [],
                ], 400);
            }

            $dateTime = now();
            $images = [];

            if (isset($request->images)) {
                foreach ($request->images as $file) {
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

            if (count($images)) {
                DisputeOrder::create([
                    'subject' => $request->subject,
                    'description' => $request->description,
                    'order_id' => $order->id,
                    'reported_id' => $userId,
                    'reported_by' => 'customer',
                ]);
                OrderImage::insert($images);
                $order->update([
                    'dispute_status' => 'Yes',
                    'dispute_date' => $dateTime,
                    'cancellation_note' => $request->description,
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Your dispute was submitted successfully. We will contact you soon.',
                'data' => $order,
            ], 200);
        } catch (\Throwable $e) {
            Log::error("Error: ", ['message' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => [
                    'errors' => [],
                ],
            ], 500);
        }
    }


    public function getDisputedOrders(Request $request, $type)
    {
        try {
            $user = auth()->user();
            // $type = $request->input('type');

            if ($type == 'borrower') {
                // If type is borrower, retrieve orders where user_id is the authenticated user's ID
                $orders = Order::where('user_id', $user->id)
                    ->whereIn('dispute_status', ['Yes', 'Resolved'])
                    ->get();
            } elseif ($type == 'lender') {
                // If type is lender, retrieve orders where retailer_id is the authenticated user's ID
                $orders = Order::where('retailer_id', $user->id)
                    ->whereIn('dispute_status', ['Yes', 'Resolved'])
                    ->get();
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid type provided',
                    'data' => [],
                ], 400);
            }

            // Loop through each order to retrieve the related product
            $orders = $orders->map(function ($order) {
                $product = Product::withTrashed()->where('id', $order->product_id)->first();
                $borrower = User::withTrashed()->where('id', $order->user_id)->first();
                $lender = User::withTrashed()->where('id', $order->retailer_id)->first();
                $dispute = DisputeOrder::where('order_id', $order->id)->first();
                $query = Query::where('id', $order->query_id)->first();

                return [
                    'order_id' => $order->id,
                    'product_id' => $product->id ?? null,
                    'product_name' => $product->name ?? null,
                    'product_image_url' => $product->thumbnailImage->file_path ?? null,
                    'dispute_status' => $order->dispute_status,
                    'order_status' => $order->status,
                    'price' => $order->price,
                    'borrower' => $borrower->name,
                    'lender' => $lender->name,
                    'dispute' => $dispute,
                    'redirect_id' => $query->id,
                    'status' => $order->dispute_status,
                    // 'cancellation note'=>$order->cancellation_note,
                    // 'created_at' => $order->created_at,
                ];
            });

            return response()->json([
                'status' => true,
                'message' => 'Disputed orders retrieved successfully',
                'data' => $orders,
            ], 200);
        } catch (\Throwable $e) {
            Log::error("Error: ", ['message' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ], 500);
        }
    }


    public function cancelOrderApi(Request $request, $id)
    {
        try {
            $order = Order::where('id', $id)->first();
            if (!$order) {
                return response()->json([
                    'status'  => false,
                    'message' => "Order not found",
                    'data'    => []
                ], 404);
            }

            $order->load(["transaction", "retailer", "queryOf"]);

            $order_commission = AdminSetting::where('key', 'order_commission')->first();

            if (in_array($order->dispute_status, ['Yes', 'Resolved'])) {
                return response()->json([
                    'status'  => false,
                    'message' => "You cannot cancel the disputed order",
                    'data'    => []
                ], 403);
            }

            if ($order->status != "Waiting") {
                return response()->json([
                    'status'  => false,
                    'message' => __("order.messages.cancel.notAllowed"),
                    'data'    => []
                ], 403);
            }

            if (empty($order->transaction->payment_id)) {
                return response()->json([
                    'status'  => false,
                    'message' => __("order.messages.cancel.paymentIncomplete"),
                    'data'    => []
                ], 403);
            }

            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

            // Retrieve Payment Intent Details
            $paymentIntentData = $stripe->paymentIntents->retrieve(
                $order->transaction->payment_id
            );

            if (!isset($paymentIntentData->latest_charge)) {
                return response()->json([
                    'status'  => false,
                    'message' => __("order.messages.cancel.paymentIncomplete"),
                    'data'    => []
                ], 403);
            }

            // Calculate Refund Amount
            if (isset($order->queryOf->negotiate_price)) {
                $amount = $order->queryOf->negotiate_price * ($order_commission->value / 100);
                $customerAmount = $order->total - $amount;
            } else {
                $amount = $order->queryOf->getCalculatedPrice($order->queryOf->date_range) * ($order_commission->value / 100);
                $customerAmount = $order->total - $amount;
            }

            // Convert to cents
            $customerAmountInCents = intval($customerAmount * 100);

            // Refund Payment
            $refundAmount = ($order->cancellation_time_left >= 2) ? $customerAmountInCents : intval($customerAmountInCents / 2);

            $refundStatus = $stripe->refunds->create([
                'charge' => $paymentIntentData->latest_charge,
                'amount' => $refundAmount,
            ]);

            if ($refundStatus->status == "succeeded") {
                $dateTime = now();

                // Update Order Status
                $order->update([
                    "status"           => "Cancelled",
                    "cancelled_date"   => $dateTime,
                    'cancellation_note' => $request->cancellation_note
                ]);

                // Update Transaction
                $order->transaction->update([
                    "status" => "Cancelled"
                ]);

                // Remove Product Unavailable Dates
                ProductDisableDate::where('product_id', $order->product_id)
                    ->whereBetween('disable_date', [$order->from_date, $order->to_date])
                    ->delete();

                    if($request->type=='lender')
                    {
                        $forUser = User::where('id', $order->user_id)->with('usernotification', 'pushToken')->first();

                    if ($forUser && $forUser->usernotification && $forUser->usernotification->order_canceled_by_lender == '1') {
                        $payload['id'] = $id;
                        $payload['content'] = "Lender cancel your order";
                        $payload['role'] = 'lender';
                        $payload['type'] = 'order';

                        if ($forUser->pushToken) {
                            sendPushNotifications($forUser->pushToken->fcm_token, $payload);
                        }
                    }
                    }
                    if($request->type=='borrower')
                    {
                        $forUser = User::where('id', $order->retailer_id)->with('usernotification', 'pushToken')->first();

                        if ($forUser && $forUser->usernotification && $forUser->usernotification->order_canceled_by_customer == '1') {
                            $payload['id'] = $id;
                            $payload['content'] = "Borrower cancel your received order";
                            $payload['role'] = 'borrower';
                            $payload['type'] = 'order';

                            if ($forUser->pushToken) {
                                sendPushNotifications($forUser->pushToken->fcm_token, $payload);
                            }
                        }

                    }

                return response()->json([
                    'status'  => true,
                    'message' => __("order.messages.cancel.success"),
                    'data'    => [
                        'order_id' => $order->id,
                        'cancelled_date' => $dateTime
                    ]
                ], 200);

            }

            return response()->json([
                'status'  => false,
                'message' => __("order.messages.cancel.error"),
                'data'    => []
            ], 500);
        } catch (\Exception $e) {
            Log::error("Order Cancellation Error: ", ['message' => $e->getMessage()]);
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
                'data'    => []
            ], 500);
        }
    }
}
