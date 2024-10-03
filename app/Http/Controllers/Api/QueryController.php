<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Query;
use App\Models\User;
use App\Models\UserDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QueryController extends Controller
{
    public function store(Request $request)
    {
        try {
            $user = auth()->user();
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
                'query_message' => 'nullable|string',
                'start' => 'required|date',
                'end' => 'required|date',
                'delivery_option' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $product = Product::where('id', $request->product_id)->first();

            $startDate = Carbon::parse($request->start)->format('Y-m-d');
            $endDate = Carbon::parse($request->end)->format('Y-m-d');

            if (empty($endDate)) {
                $endDate = $startDate;
            }

            $query = Query::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id,
                'for_user' => $product->user_id,
                'query_message' => $request->query_message,
                'status' => 'PENDING',
                'date_range' => $startDate . ' - ' . $endDate,
                'delivery_option' => $request->delivery_option,
                'address_id' => $request->address_id,
            ]);
            // $forUser = User::Where('id', $product->user_id)->with('usernotification')->get();
            // dd('here');
            $forUser = User::where('id', $product->user_id)->with('usernotification', 'pushToken')->first();

            if ($forUser && $forUser->usernotification && $forUser->usernotification->query_receive == '1') {
                $payload['id'] = $query->id;
                $payload['content'] = "You have received an inquiry for product #" . str_pad($product->name, 5, '0', STR_PAD_LEFT);
                $payload['role'] = 'lender';
                $payload['type'] = 'inquiry';

                // Check if pushToken exists to avoid errors
                if ($forUser->pushToken) {
                    sendPushNotifications($forUser->pushToken->fcm_token, $payload);
                }
            }


            return response()->json([
                'status' => true,
                'message' => 'Query created successfully',
                'data' => $query,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function queriesByUser(Request $request)
    {
        $user = auth()->user();
        $status = $request->get('status');
        try {
            $queries = Query::where('user_id', $user->id)
                ->whereNull('deleted_at')
                ->filterByStatus($status)
                ->get();
            // dd($queries);

            if (count($queries) > 0) {
                $queries = $queries->map(function ($query) {
                    [$startDate, $endDate] = explode(' - ', $query->date_range);
                    // dd( $startDate , $endDate );
                    $productId = $query->product_id;
                    $product = Product::where('id', $productId)
                        ->whereNull('deleted_at')
                        ->first();
                    $lender = User::where('id', $query->for_user)->first();
                    $price = $query->negotiate_price  ?? $query->getCalculatedPrice($query->date_range);
                    $price = $price + ($query->cleaning_charges) + ($query->shipping_charges);
                    $actual_price = $query->getCalculatedPrice($query->date_range);
                    // $price = $price + ($query->cleaning_charges) + ($query->shipping_charges);
                    // $actual_price =$query->getCalculatedPrice($query->date_range);
                    // $price = $price + ($query->cleaning_charges) + ($query->shipping_charges);

                    if ($query->delivery_option == 'pick_up') {
                        $address = $product->productCompleteLocation ?? Null;
                    } else {
                        $delivery_address = UserDetail::where('id',$query->address_id)->first();
                        $address = $delivery_address ?? NUll;
                    }
                    // dd($price);

                    return [
                        'id' => $query->id,
                        'user_id' => $query->user_id,
                        'product_id' => $query->product_id,
                        'for_user' => $query->for_user,
                        'query_message' => $query->query_message,
                        'status' => $query->status,
                        'date_range' => [
                            'start' => $startDate,
                            'end' => $endDate,
                        ],
                        'price' => $price,
                        'name' => $product->name ?? null,
                        'product_image_url' => $product->thumbnailImage->file_path ?? null,
                        'lender' => $lender->name ?? null,
                        'lender_profile_pic' => $lender->frontend_profile_url ?? null,
                        'lender_id' => $lender->id ?? null,
                        'created_at' => $query->created_at,
                        'negotiated_price' => $query->negotiate_price ?? Null,
                        'cleaning_charge' => $query->cleaning_charges ?? Null,
                        'shipping_charge' => $query->shipping_charges ?? null,
                        'actual_price' => $actual_price ?? null,
                        'shipment_type' => $query->delivery_option,
                        'address' => $address,
                    ];
                });

                return response()->json([
                    'status' => true,
                    'message' => 'Query fetched successfully!',
                    'data' => $queries,
                ], 200);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'No query is available',
                    'data' => $queries,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function queriesForUser(Request $request)
    {
        $user = auth()->user();
        // dd($user);
        $status = $request->get('status');
        try {
            $queries = Query::where('for_user', $user->id)
                ->whereNull('deleted_at')
                ->filterByStatus($status)
                ->get();

            if ($queries->count() > 0) {
                $queries = $queries->map(function ($query) {
                    [$startDate, $endDate] = explode(' - ', $query->date_range);
                    // dd($startDate, $endDate,$query);
                    $productId = $query->product_id;
                    // dd($productId,$query);
                    $product = Product::where('id', $productId)
                        ->whereNull('deleted_at')
                        ->first();
                    // dd($product);
                    $borrower = User::where('id', $query->user_id)->first();
                    // dd($borrower);
                    $price = $query->negotiate_price ?? $query->getCalculatedPrice($query->date_range);
                    $actual_price = $query->getCalculatedPrice($query->date_range);
                    if ($query->delivery_option == 'pick_up') {
                        $address = $product->productCompleteLocation ?? null;
                    } else {
                        $delivery_address = UserDetail::where('id',$query->address_id)->first();
                        $address = $delivery_address ?? NUll;
                    }
                    return [
                        'id' => $query->id ?? null,
                        'user_id' => $query->user_id ?? null,
                        'product_id' => $query->product_id ?? null,
                        'for_user' => $query->for_user,
                        'query_message' => $query->query_message,
                        'status' => $query->status,
                        'date_range' => [
                            'start' => $startDate,
                            'end' => $endDate,
                        ],
                        'price' => $price,
                        'name' => $product->name ?? null,
                        'product_image_url' => $product->thumbnailImage->file_path ?? null,
                        'borrower' => $borrower->name ?? null,
                        'borrower_profile_pic' => $borrower->frontend_profile_url ?? null,
                        'borrower_id' => $borrower->id ?? null,
                        'created_at' => $query->created_at,
                        'negotiated_price' => $query->negotiate_price ?? Null,
                        'cleaning_charge' => $query->cleaning_charges ?? Null,
                        'shipping_charge' => $query->shipping_charges ?? null,
                        'actual_price' => $actual_price ?? null,
                        'shipment_type' => $query->delivery_option,
                        'address' => $address,
                    ];
                });

                return response()->json([
                    'status' => true,
                    'message' => 'Query fetched successfully!',
                    'data' => $queries,
                ], 200);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'No query is available',
                    'data' => $queries,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function updateQueryStatus(Request $request, $id, $type)
    {
        try {
            $query_details = Query::findOrFail($id);
            if ($type == 'ACCEPTED') {
                $query_details->update(['status' => 'ACCEPTED']);
                $forUser = User::where('id', $query_details->user_id)->with('usernotification', 'pushToken')->first();

                if ($forUser && $forUser->usernotification && $forUser->usernotification->accept_item == '1') {
                    $payload['id'] = $query_details->id;
                    $payload['content'] = "Your Inquiry get accepted ";
                    $payload['role'] = 'lender';
                    $payload['type'] = 'inquiry';

                    if ($forUser->pushToken) {
                        sendPushNotifications($forUser->pushToken->fcm_token, $payload);
                    }
                }
            } elseif ($type == 'REJECTED') {
                $query_details->update(['status' => 'REJECTED']);
                $forUser = User::where('id', $query_details->user_id)->with('usernotification', 'pushToken')->first();

                if ($forUser && $forUser->usernotification && $forUser->usernotification->reject_item == '1') {
                    $payload['id'] = $query_details->id;
                    $payload['content'] = "Your Inquiry get rejected ";
                    $payload['role'] = 'lender';
                    $payload['type'] = 'inquiry';

                    if ($forUser->pushToken) {
                        sendPushNotifications($forUser->pushToken->fcm_token, $payload);
                    }
                }
            } elseif ($type == 'price') {
                $validator = Validator::make($request->all(), [
                    // 'price' => 'required',
                    'cleaning_charges' => 'required',
                    'shipping_charges' => 'required',

                ]);
                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }
                $query_details->update([
                    'negotiate_price' => $request->price ?? null,
                    'shipping_charges' => $request->shipping_charges,
                    'cleaning_charges' => $request->cleaning_charges,
                    'status' => 'ACCEPTED',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid status type',
                    'errors' => ['type' => 'The status type must be either ACCEPTED or REJECTED.'],
                ], 400);
            }

            return response()->json([
                'status' => true,
                'message' => 'Query status updated successfully',
                'data' => $query_details,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => [],
            ], 500);
        }
    }


    public function orderManagement()
    {
        $user = auth()->user();
        try {
            $queries = Query::where('user_id', $user->id)
                ->where('status', 'completed')
                ->whereNull('deleted_at')
                ->get();

            if ($queries->count() > 0) {
                $queries = $queries->map(function ($query) {
                    [$startDate, $endDate] = explode(' - ', $query->date_range);

                    $productId = $query->product_id;
                    $product = Product::withTrashed()->where('id', $productId)->first();
                    $order = Order::where('query_id', $query->id)->first();
                    $lender = User::where('id', $query->for_user)->first();
                    $price = $query->negotiate_price ?? $query->getCalculatedPrice($query->date_range);
                    $now = Carbon::now()->format('Y-m-d');

                    if ($order && $order->status === 'Waiting') {
                        if ($now > $endDate) {
                            $status = 'COMPLETED';
                        } elseif ($now < $startDate) {
                            $status = 'PAID';
                        } else {
                            $status = 'ACTIVE';
                        }
                    } elseif ($order && $order->status == 'Completed') {
                        $status = 'COMPLETED';
                    } elseif ($order && $order->status == 'Picked Up') {
                        $status = 'ACTIVE';
                    } elseif ($order && $order->status == 'Cancelled') {
                        $status = 'CANCELLED';
                    } else {
                        $status = 'UNKNOWN';
                    }

                    return [
                        'id' => $query->id,
                        'user_id' => $query->user_id,
                        'product_id' => $query->product_id,
                        'for_user' => $query->for_user,
                        'query_message' => $query->query_message,
                        'status' => $status,
                        'date_range' => [
                            'start' => $startDate,
                            'end' => $endDate,
                        ],
                        'name' => $product->name ?? null,
                        'product_image_url' => $product->thumbnailImage->file_path ?? null,
                        'lender' => $lender->name ?? null,
                        'lender_profile_pic' => $lender->frontend_profile_url ?? null,
                        'lender_id' => $lender->id,
                        'brand' => $product->brand ?? null,
                        'size' => $product->size ?? null,
                        'price' => $price,

                    ];
                });

                return response()->json([
                    'status' => true,
                    'message' => 'Order management data fetched successfully!',
                    'data' => $queries,
                ], 200);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'No queries available for order management',
                    'data' => [],
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }



    public function booked()
    {
        $user = auth()->user();
        // dd($user);
        try {
            $queries = Query::where('for_user', $user->id)->where('status', 'completed')
                ->whereNull('deleted_at')
                ->get();
            // dd($queries);


            if ($queries->count() > 0) {
                $queries = $queries->map(function ($query) {
                    [$startDate, $endDate] = explode(' - ', $query->date_range);
                    $productId = $query->product_id;
                    $product = Product::where('id', $productId)
                        ->whereNull('deleted_at')
                        ->first();
                    $order = Order::where('query_id', $query->id)->first();
                    $borrower = User::where('id', $query->user_id)->first();
                    $price = $query->negotiate_price ?? $query->getCalculatedPrice($query->date_range);

                    $now = Carbon::now()->format('Y-m-d');

                    if ($order && $order->status === 'Waiting') {
                        if ($now > $endDate) {
                            $status = 'COMPLETED';
                        } elseif ($now < $startDate) {
                            $status = 'PAID';
                        } else {
                            $status = 'ACTIVE';
                        }
                    } elseif ($order && $order->status == 'Completed') {
                        $status = 'COMPLETED';
                    } elseif ($order && $order->status == 'Picked Up') {
                        $status = 'ACTIVE';
                    } elseif ($order && $order->status == 'Cancelled') {
                        $status = 'CANCELLED';
                    } else {
                        $status = 'UNKNOWN';
                    }

                    return [
                        'id' => $query->id,
                        'user_id' => $query->user_id,
                        'product_id' => $query->product_id,
                        'for_user' => $query->for_user,
                        'query_message' => $query->query_message,
                        'status' => $status,
                        'date_range' => [
                            'start' => $startDate,
                            'end' => $endDate,
                        ],
                        'name' => $product->name ?? null,
                        'product_image_url' => $product->thumbnailImage->file_path ?? null,
                        'borrower' => $borrower->name ?? null,
                        'borrower_profile_pic' => $borrower->frontend_profile_url ?? null,
                        'borrower_id' => $borrower->id ?? null,
                        'brand' => $product->brand ?? 'N/A',
                        'size' => $product->size ?? "N/A",
                        'price' => $price,
                    ];
                });

                return response()->json([
                    'status' => true,
                    'message' => 'Order management data fetched successfully!',
                    'data' => $queries,
                ], 200);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'No queries available for order management',
                    'data' => [],
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }



    // public function bookings(Request $request, $id)
    // {
    //     try {

    //         $query = Query::find($id);

    //         if (!$query) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Query not found',
    //             ], 404);
    //         }

    //         $paymentResponse = $request->input('payment_response');

    //         $paymentStatus = $paymentResponse['status'];
    //         $transactionId = $paymentResponse['transaction_id'];

    //         $orderStatus = ($paymentStatus === 'succeeded') ? 'Pending' : 'Failed';

    //         [$fromDate, $toDate] = explode(' - ', $query->date_range);
    //         $fromDateTime = Carbon::parse($fromDate);
    //         $toDateTime = Carbon::parse($toDate);

    //         $order = Order::create([
    //             'user_id' => $query->user_id,
    //             'location_id' => $query->product_id,
    //             'transaction_id' => $transactionId,
    //             'from_date' => $fromDateTime->toDateString(),
    //             'to_date' => $toDateTime->toDateString(),
    //             'from_hour' => $fromDateTime->format('H'),
    //             'from_minute' => $fromDateTime->format('i'),
    //             'to_hour' => $toDateTime->format('H'),
    //             'to_minute' => $toDateTime->format('i'),
    //             'order_date' => now()->toDateString(),
    //             'status' => $orderStatus,
    //         ]);
    //         $query->update(['status' => 'COMPLETED']);

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Booking recorded successfully',
    //             'data' => $order,
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => $e->getMessage(),
    //         ], 500);
    //     }
    // }



}
