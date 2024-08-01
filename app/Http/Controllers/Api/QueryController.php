<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Query;
use App\Models\User;
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
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $product = Product::where('id', $request->product_id)->first();

            $startDate = Carbon::parse($request->start)->format('d-m-Y');
            $endDate = Carbon::parse($request->end)->format('d-m-Y');

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
            ]);

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

            if (count($queries) > 0) {
                $queries = $queries->map(function ($query) {
                    [$startDate, $endDate] = explode(' - ', $query->date_range);
                    $productId = $query->product_id;
                    $product = Product::where('id', $productId)
                        ->whereNull('deleted_at')
                        ->first();
                    $lender = User::where('id', $query->for_user)->first();
                    $price = $query->negotiate_price ?? $product->getCalculatedPrice($query->date_range);

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
                        'lender_profile_pic' => $lender->frontend_profile_url,
                        'lender_id' => $lender->id,
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
                    $product = Product::where('id', $productId)
                        ->whereNull('deleted_at')
                        ->first();
                    $borrower = User::where('id', $query->user_id)->first();
                    $price = $query->negotiate_price ?? $product->getCalculatedPrice($query->date_range);
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
                        'borrower' => $borrower->name ?? null,
                        'borrower_profile_pic' => $borrower->frontend_profile_url,
                        'borrower_id' => $borrower->id,
                    ];
                });
                // dd('here');
                // dd('here',$price);

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
            } elseif ($type == 'REJECTED') {
                $query_details->update(['status' => 'REJECTED']);
            } elseif ($type == 'price') {
                $validator = Validator::make($request->all(), [
                    'price' => 'required',
                ]);
                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }
                $query_details->update(['negotiate_price' => $request->price, 'status' => 'ACCEPTED']);
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
            $queries = Query::where('user_id', $user->id)->where('status', 'completed')
                ->whereNull('deleted_at')
                ->get();
            // dd($queries);
            if ($queries->count() > 0) {
                $queries = $queries->map(function ($query) {
                    [$startDate, $endDate] = explode(' - ', $query->date_range);

                    // Convert date format
                    $startDate = Carbon::createFromFormat('d-m-Y', $startDate)->format('Y-m-d');
                    $endDate = Carbon::createFromFormat('d-m-Y', $endDate)->format('Y-m-d');
                    $productId = $query->product_id;
                    $product = Product::where('id', $productId)
                        ->whereNull('deleted_at')
                        ->first();
                    $lender = User::where('id', $query->for_user)->first();
                    $price = $query->negotiate_price ?? $product->getCalculatedPrice($query->date_range);
                    $now = Carbon::now()->format('Y-m-d');
                    if ($now > $endDate) {
                        $status = 'COMPLETED';
                    } elseif ($now < $startDate) {
                        $status = 'PAID';
                    } else {
                        $status = 'ACTIVE';
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
                        'lender_profile_pic' => $lender->frontend_profile_url,
                        'lender_id' => $lender->id,
                        'brand' => $product->get_brand->name,
                        'size' => $product->get_size->name,
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

            if ($queries->count() > 0) {
                $queries = $queries->map(function ($query) {
                    [$startDate, $endDate] = explode(' - ', $query->date_range);

                    // Convert date format
                    $startDate = Carbon::createFromFormat('d-m-Y', $startDate)->format('Y-m-d');
                    $endDate = Carbon::createFromFormat('d-m-Y', $endDate)->format('Y-m-d');

                    $productId = $query->product_id;
                    $product = Product::where('id', $productId)
                        ->whereNull('deleted_at')
                        ->first();
                    $borrower = User::where('id', $query->user_id)->first();
                    $price = $query->negotiate_price ?? $product->getCalculatedPrice($query->date_range);

                    $now = Carbon::now()->format('Y-m-d');

                    if ($now > $endDate) {
                        $status = 'COMPLETED';
                    } elseif ($now < $startDate) {
                        $status = 'PAID';
                    } else {
                        $status = 'ACTIVE';
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
                        'borrower_profile_pic' => $borrower->frontend_profile_url,
                        'borrower_id' => $borrower->id,
                        'brand' => $product->get_brand->name,
                        'size' => $product->get_size->name,
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
