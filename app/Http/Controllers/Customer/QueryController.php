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

            // $dates = explode(' - ', $request->rental_dates);
            // $startDate = date('Y-m-d', strtotime($dates[0]));
            // $endDate = date('Y-m-d', strtotime($dates[1]));


            // $product = Product::findorfail($product_id);
            // foreach($product->disableDates as $disabled_date)
            // {
            //     if($startDate<= $disabled_date && $endDate>= $disabled_date )
            //     throw new \Exception("Product is not available in this date range");
            // }


            // dd($data);
            $qur = Query::create($data);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Query send successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'There was an error processing your request: ' . $e->getMessage()]);
        }
    }


    public function myQuery(Request $request)
    {
        $user = auth()->user();
        $querydatas = Query::where(['user_id' => $user->id, 'status' => 'PENDING'])->get();
        return view('customer.my_query_list', compact('querydatas'));
    }

    public function view(Request $request)
    {
        $query_id = $request->query_id;
        $query = Query::findOrFail($query_id);

        $view = view('customer.query_product', compact('query'))->render();

        return response()->json(['success' => true, 'data' => $view]);
    }

    public function receiveQuery(Request $request)
    {
        $user = auth()->user();
        $querydatas = Query::where(['for_user' => $user->id, 'status' => 'PENDING'])->get();
        $accept = true;
        return view('customer.receive_query_list', compact('querydatas', 'accept'));
    }

    public function acceptQuery(Request $request, $id)
    {


        $query_product = Query::where('id', $id)->first();


        // dd($query_product->toArray());

                // $query->FilterByDateRange($startDate,$endDate);
        $data = [
            // 'user_id' => $query_product->user_id,
            // 'product_id' => $query_product->product_id,
            'negotiate_price' => $request->negotiate_price ?? null,
            'shipping_charges' => $request->shipping_charges ?? null,
            'cleaning_charges' => $request->cleaning_charges ?? null,

            // 'for_user' => $query_product->for_user,
            // 'query_message' => $query_product->query_message,
            'status' => 'ACCEPTED',
            // 'date_range' => $query_product->date_range,
        ];

        $query_product->update($data);

        $dates = explode(' - ', $query_product->date_range);
        $startDate = date('Y-m-d', strtotime($dates[0]));
        $endDate = date('Y-m-d', strtotime($dates[1]));

        while ($startDate <= $endDate) {
            $startDate = date_create($startDate);
             $query_product->product->disableDates()->create([
            'disable_date' => $startDate->format('Y-m-d'),
            ]);


            $startDate->modify('+1 day');
            $startDate = $startDate->format('Y-m-d');
        }



        return redirect()->back()->with('success', 'Query accepted successfully.');
    }
    public function rejectQuery(Request $request, $id)
    {


        $query_product = Query::where('id', $id)->first();

        $data = [
            // 'user_id' => $query_product->user_id,
            // 'product_id' => $query_product->product_id,
            // 'for_user' => $query_product->for_user,
            // 'query_message' => $query_product->query_message,
            'status' => 'REJECTED',
            // 'date_range' => $query_product->date_range,
        ];

        $query_product->update($data);

        return redirect()->back()->with('success', 'Query rejected successfully.');
    }

    public function fetchQueries(Request $request)
    {
        $userType = $request->user;
        $status = $request->status;
        if ($userType == 'borrower') {
            $queries = Query::where(['status' => $status, 'user_id' => auth()->user()->id])->get();
            $html = view('components.product-query', ['querydatas' => $queries])->render();
        } else {
            $accept = ($status == 'ACCEPTED') || ($status == 'REJECTED') || ($status == 'COMPLETED') ? false : true;
            $queries = Query::where(['status' => $status, 'for_user' => auth()->user()->id])->get();
            $html = view('components.receive-query', ['querydatas' => $queries, 'accept' => $accept])->render();
        }

        return response()->json([
            'success' => true,
            'html' => $html,
        ]);
    }
}
