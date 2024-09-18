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
        DB::beginTransaction();

        try {
            $request->validate([
                'rental_dates' => 'required',
                'product_id' => 'required',
                'description' => 'required|string',
                'delivery_option' => 'required|string',
            ]);


            $user = auth()->user();
            $foruser = jsdecode_userdata($request->for_user);
            $product_id = jsdecode_userdata($request->product_id);

            $lender = User::where('id', $foruser)->first();

            $dates = explode(' - ', $request->rental_dates);
            if (count($dates) !== 2) {
                throw new \Exception("Invalid date range format.");
            }

            $startDate = date('Y-m-d', strtotime($dates[0]));
            $endDate = date('Y-m-d', strtotime($dates[1]));

            $totalDays = (strtotime($endDate) - strtotime($startDate)) / 86400 + 1;

            $product = Product::findOrFail($product_id);
            if ($totalDays < $product->min_days_rent_item) {
                throw new \Exception("The rental period must be at least {$product->min_days_rent_item} days.");
            }

            foreach ($product->disableDates as $disabled_date) {
                if ($startDate <= $disabled_date->disable_date && $endDate >= $disabled_date->disable_date) {
                    throw new \Exception("Product is not available in this date range.");
                }
            }

            $queryDates = Query::where([
                'product_id' => $product_id,
                'status' => 'PENDING',
                'user_id' => auth()->user()->id
            ])->get()->pluck('date_range');

            foreach ($queryDates as $queryDate) {
                $queryDatesArray = explode(' - ', $queryDate);

                if (count($queryDatesArray) !== 2) {
                    throw new \Exception("Stored query date range format is invalid: {$queryDate}");
                }

                $queryStartDate = date('Y-m-d', strtotime($queryDatesArray[0]));
                $queryEndDate = date('Y-m-d', strtotime($queryDatesArray[1]));

                if (($startDate >= $queryStartDate && $startDate <= $queryEndDate) ||
                    ($endDate >= $queryStartDate && $endDate <= $queryEndDate) ||
                    ($startDate <= $queryStartDate && $endDate >= $queryEndDate)
                ) {
                    throw new \Exception("Product is already reserved for this date range.");
                }
            }

            $data = [
                'user_id' => $user->id,
                'product_id' => $product_id,
                'for_user' => $foruser,
                'query_message' => $request->description,
                'status' => 'PENDING',
                'date_range' => $startDate . ' - ' . $endDate,
                'delivery_option' => $request->delivery_option,
            ];

            Query::create($data);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Inquiry sent successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }





    public function myQuery(Request $request)
    {
        $user = auth()->user();
        $status = $request->get('status', 'PENDING'); // Default to 'PENDING' if no status is passed
        $querydatas = Query::where(['user_id' => $user->id, 'status' => $status])->orderByDesc('id')->get();
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
        $status = $request->query('status', 'PENDING');
        $querydatas = Query::where(['for_user' => $user->id, 'status' => $status])->orderBy('created_at', 'desc')->get();
        $accept = true;
        return view('customer.receive_query_list', compact('querydatas', 'accept'));
    }

    public function acceptQuery(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            // Validator::make($request->all(), [
            //     'cleaning_charges' => 'required|numeric|min:1',
            //     'shipping_charges' => 'required|numeric',
            // ])->validate();

            $query_product = Query::where('id', $id)->first();


            // dd($query_product->toArray());

            // $query->FilterByDateRange($startDate,$endDate);
            $data = [

                'negotiate_price' => $request->negotiate_price ?? null,
                'shipping_charges' => $request->shipping_charges ?? null,
                'cleaning_charges' => $request->cleaning_charges ?? null,

                'status' => 'ACCEPTED',
                // 'date_range' => $query_product->date_range,
            ];

            $query_product->update($data);

            // $dates = explode(' - ', $query_product->date_range);
            // $startDate = date('Y-m-d', strtotime($dates[0]));
            // $endDate = date('Y-m-d', strtotime($dates[1]));

            // while ($startDate <= $endDate) {
            //     $startDate = date_create($startDate);
            //     $query_product->product->disableDates()->create([
            //         'disable_date' => $startDate->format('Y-m-d'),
            //     ]);


            //     $startDate->modify('+1 day');
            //     $startDate = $startDate->format('Y-m-d');
            // }


            $userId = jsencode_userdata($query_product->user->id);
            if (@$query_product->user->usernotification->accept_item == '1') {
                $query_product->user->notify(new AcceptItem($userId));
            }

            DB::commit();
            return redirect()->back()->with('success', 'Offer sent successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
            // return response()->json(['success' => false, 'message' => $e->getMessage() ]);
        }
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

        $userId = jsencode_userdata($query_product->user->id);
        if (@$query_product->user->usernotification->reject_item == '1') {
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
