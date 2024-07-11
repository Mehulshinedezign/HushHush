<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QueryController extends Controller
{
    public function store(Request $request)
    {
        // dd(auth()->user());
        try {
            $user = auth()->user();
            $validator = Validator::make($request->all(), [
                // 'user_id'=>$user->id,
                'product_id' => 'required|exists:products,id',
                // 'for_user' => 'required|exists:users,id',
                'query_message' => 'required|string',
                // 'status' => 'required|string',
                // 'date_range' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $product =Product::where('id', $request->product_id)->first();
            $query = Query::create(
                [
                    'user_id' => $user->id,
                    'product_id' => $request->product_id,
                    'for_user' => $product->user_id,
                    'query_message' => $request->query_message,
                    'status' => 'PENDING',
                    'date_range' => 'required',


                ]
            );
            return response()->json([
                'status' => true,
                'message' => 'Query created successfully',
                'data' => $query,
            ], 200);
            // return response()->json(['message' => 'Query created successfully', 'data' => $query], 201);
        } catch (\Exception $e) {return response()->json([
            'status' => false,
            'message' => $e->getMessage(),
        ], 500);
        }
    }

    public function queriesByUser($user_id)
    {
        try {
            $queries = Query::where('user_id', $user_id)->get();
            if(count($queries) > 0) {
                return response()->json([
                    'status' => true,
                    'message' => 'Query fetched successfully!',
                    'data' => $queries,
                ], 200);
            }
        else {
            return response()->json([
                'status' => true,
                'message' => 'No query is available',
                'data' => $queries,
            ], 400);
        }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function queriesForUser($for_user)
    {
        try {
            $queries = Query::where('for_user', $for_user)->get();
            if(count($queries) > 0) {
                return response()->json([
                    'status' => true,
                    'message' => 'Query fetched successfully!',
                    'data' => $queries,
                ], 200);
            }
            else {
                return response()->json([
                    'status' => true,
                    'message' => 'No query is available',
                    'data' => $queries,
                ], 400);

            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
