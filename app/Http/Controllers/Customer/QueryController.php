<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Query;
use App\Models\User;
use App\Notifications\AcceptItem;
use App\Notifications\QueryReceived;
use App\Notifications\RejectItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class QueryController extends Controller
{
    public function store(Request $request)
    {
        // Validate incoming request


        DB::beginTransaction();

        try {
            $request->validate([
                'rental_dates' => 'required',
                'product_id' => 'required',
                'rental_dates' => 'required',
                'description' => 'required|string',
            ]);
            $user = auth()->user();
            $foruser = jsdecode_userdata($request->for_user);
            $product_id = jsdecode_userdata($request->product_id);
            // dd($request);
            $lender = User::where('id',$foruser)->first();
            $data = [
                'user_id' => $user->id,
                'product_id' => $product_id,
                'for_user' => $foruser,
                'query_message' => $request->description,
                'status' => 'PENDING',
                'date_range' => $request->rental_dates,
            ];

            $dates = explode(' - ', $request->rental_dates);
            $startDate = date('Y-m-d', strtotime($dates[0]));
            $endDate = date('Y-m-d', strtotime($dates[1]));

            // dd($dates, $startDate, $endDate);


            $product = Product::findorfail($product_id);
            foreach($product->disableDates as $disabled_date)
            {
                if($startDate <= $disabled_date->disable_date && $endDate>= $disabled_date->disable_date )
                throw new \Exception("Product is not available in this date range");
            }

            $querydates = Query::where(['product_id'=>$product_id, 'status'=> 'PENDING','user_id' =>auth()->user()->id])->get()->pluck('date_range');
             foreach($querydates as $query_date)
             {
                $dates = explode(' - ', $query_date);
                $querystartDate = date('Y-m-d', strtotime($dates[0]));
                $queryendDate = date('Y-m-d', strtotime($dates[1]));

                if($startDate <= $querystartDate && $endDate>= $queryendDate )
                throw new \Exception("Product is not available in this date range");
             }


            // dd($data);
            $qur = Query::create($data);

            $product_date=[
                'customer_name'=>$user->name,
                'date' =>$request->rental_dates,
                'query_message' => $request->description,
                'lender_id' =>$foruser,
            ];
            if(@$lender->usernotification->query_receive == '1'){
                $lender->notify(new QueryReceived($product_date));
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Query send successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage() ]);
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


        $userId =jsencode_userdata($query_product->user->id);
        if(@$query_product->user->usernotification->accept_item == '1'){
            $query_product->user->notify(new AcceptItem($userId));
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

        $userId =jsencode_userdata($query_product->user->id);
        if(@$query_product->user->usernotification->reject_item == '1'){
            $query_product->user->notify(new RejectItem($userId));
        }
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
