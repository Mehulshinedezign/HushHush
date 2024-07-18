<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class QueryController extends Controller
{
    public function store(Request $request)
    {
        // Validate incoming request

        $request->validate([
            'rental_dates' =>'required',
            'product_id' => 'required',
            'description' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $user = auth()->user();
            $foruser = jsdecode_userdata($request->for_user);
            $product_id = jsdecode_userdata($request->product_id);

            $data = [
                'user_id' => $user->id,
                'product_id' => $product_id,
                'for_user' => $foruser,
                'query_message' => $request->description,
                'status' => 'PENDING',
                'date_range' => $request->rental_dates,
            ];

            $qur = Query::create($data);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Query send successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'There was an error processing your request: ' . $e->getMessage()]);
        }
    }

    public function myQuery(Request $request){
        $user = auth()->user();
        $querydatas = Query::where('user_id',$user->id)->get();
        return view('customer.my_query_list',compact('querydatas'));
    }

    public function view(Request $request)
    {
        $product_id = $request->product_id;
        $product = Product::findOrFail($product_id);


        $view = view('customer.query_product', compact('product'))->render();

        return response()->json(['success' => true, 'data' => $view]);

    }

    public function receiveQuery(Request $request){
        $user = auth()->user();
        $querydatas = Query::where('for_user',$user->id)->get();
       
        return view('customer.receive_query_list',compact('querydatas'));
    }

    public function acceptQuery(Request $request,$id){


        $query_product = Query::where('id',$id)->first();

        // dd($query_product);
        $data = [
            'user_id' => $query_product->user_id,
            'product_id' => $query_product->product_id,
            'negotiate_price' => $request->negotiate_price ?? null,
            'for_user' => $query_product->for_user,
            'query_message' => $query_product->query_message,
            'status' => 'ACCEPTED',
            'date_range' => $query_product->date_range,
        ];

        $query_product->update($data);

        return redirect()->back()->with('success', 'Query accepted successfully.');
    }

    public function rejectQuery(Request $request,$id){

    
        $query_product = Query::where('id',$id)->first();

        $data = [
            'user_id' => $query_product->user_id,
            'product_id' => $query_product->product_id,
            'for_user' => $query_product->for_user,
            'query_message' => $query_product->query_message,
            'status' => 'REJECTED',
            'date_range' => $query_product->date_range,
        ];

        $query_product->update($data);

        return redirect()->back()->with('success', 'Query rejected successfully.');
    }
}
