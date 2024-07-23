<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QueryController extends Controller
{
    public function store(Request $request)
    {
        // Validate incoming request

        $request->validate([
            'rental_dates' => 'required',
            'product_id' => 'required',
            'rental_dates' => 'required',
            'description' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $user = auth()->user();
            $foruser = jsdecode_userdata($request->for_user);
            $product_id = jsdecode_userdata($request->product_id);
            // dd($request);
            $data = [
                'user_id' => $user->id,
                'product_id' => $product_id,
                'for_user' => $foruser,
                'query_message' => $request->description,
                'status' => 'PENDING',
                'date_range' => $request->rental_dates,
            ];
            // dd($data);
            $qur = Query::create($data);

            // create chat

            $product = Product::where('id', $product_id)->first();
            $receiver_id = $product->user_id;
            $sent_by = auth()->user()->role_id == '3' ? 'Customer' : 'Retailer';

            if (check_chat_exist_or_not($receiver_id))
                $chat = check_chat_exist_or_not($receiver_id);
            else
                $chat = auth()->user()->chat()->create(['chatid' => str::random(10), 'retailer_id' => $receiver_id, 'order_id' => null, 'sent_by' => $sent_by]);


            DB::commit();
            return response()->json(['success' => true, 'message' => 'Query send successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'There was an error processing your request: ' . $e->getMessage()]);
        }
    }

    public function myQuery(Request $request)
    {
    public function myQuery(Request $request)
    {
        $user = auth()->user();
        $querydatas = Query::where('user_id', $user->id)->get();

        return view('customer.query_list', compact('querydatas'));
    }

    public function view(Request $request)
    {
        $product_id = $request->product_id;
        $product = Product::findOrFail($product_id);


        $view = view('customer.query_product', compact('product'))->render();

        return response()->json(['success' => true, 'data' => $view]);
    }
}
