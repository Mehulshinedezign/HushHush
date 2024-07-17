<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Query;
use App\Models\User;
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
                'start' => 'required',
                'end' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $product = Product::where('id', $request->product_id)->first();


            $startDate = $request->start;
            $endDate = $request->end;


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
                        'name' => $product->name ?? null,
                        'product_image_url' => $product->thumbnailImage->file_path ?? null,
                        'lender' => $lender->name ?? null,
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
                    $productId = $query->product_id;
                    $product = Product::where('id', $productId)
                        ->whereNull('deleted_at')
                        ->first();
                    $borrower = User::where('id', $query->user_id)->first();

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
                        'name' => $product->name ?? null,
                        'product_image_url' => $product->thumbnailImage->file_path ?? null,
                        'borrower' => $borrower->name ?? null,
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




    public function updateQueryStatus($id, $type)
    {
        // dd('here');
        try {
            $query = Query::findOrFail($id);

            if ($type == 'ACCEPTED') {
                $query = Query::where('id', $id)->update(['status' => 'ACCEPTED']);
                return response()->json([
                    'status' => true,
                    'message' => 'Query status updated successfully',
                    'data' => $query,
                ], 200);
            } elseif ($type == 'REJECTED') {
                $query = Query::where('id', $id)->update(['status' => 'REJECTED']);
                return response()->json([
                    'status' => true,
                    'message' => 'Query status updated successfully',
                    'data' => $query,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid status type',
                    'errors' => ['type' => 'The status type must be either ACCEPTED or REJECTED.'],
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => [],
            ], 500);
        }
    }
}
