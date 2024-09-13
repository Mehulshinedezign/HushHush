<?php

namespace App\Http\Controllers;

use App\Exports\PayoutsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\{User, AdminSetting, CmsPage, RetailerPayout, Order, DisputeOrder, OrderItem, ProductUnavailability, RefundSecurity, Product};
use App\Notifications\Payment;
use DateTime, DB, Exception, Stripe;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function __construct()
    {
        $this->stripe = Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $this->stripeClient = new Stripe\StripeClient(env('STRIPE_SECRET'));
    }

    public function index(Request $request)
    {
        $revenueChartDetails = ["months" => [], "highestAmount" => [], "lowestAmount" => [], "totalOrders" => []];
        $fromDate = $toDate = null;
        $format = 'M Y';
        if (isset($request->date)) {
            $fromAndToDate = array_map('trim', explode($request->global_date_separator, $request->date));
            $fromstartDate = date($request->global_date_format_for_check, strtotime($fromAndToDate[0]));
            $fromendDate = date($request->global_date_format_for_check, strtotime($fromAndToDate[1]));
            if (count($fromAndToDate) == 2) {
                $fromDate = DateTime::createFromFormat($request->global_date_format_for_check, $fromstartDate)->format('Y-m-d');
                $toDate = DateTime::createFromFormat($request->global_date_format_for_check, $fromendDate)->format('Y-m-d');
                $format = 'Y-m-d';
            }
        }

        $orders = Order::select('*', DB::raw('MONTH(created_at) as month'))->when(!is_null($fromDate) && !is_null($toDate), function ($q) use ($fromDate, $toDate) {
            $q->whereDate("created_at", ">=", $fromDate)->whereDate("created_at", "<=", $toDate);
        })->get();
        $totalOrderAmount = $orders->sum('total');
        $users = User::where("role_id", "!=", "1")->when(!is_null($fromDate) && !is_null($toDate), function ($q) use ($fromDate, $toDate) {
            $q->whereDate("created_at", ">=", $fromDate)->whereDate("created_at", "<=", $toDate);
        })->get();
        $products = Product::when(!is_null($fromDate) && !is_null($toDate), function ($q) use ($fromDate, $toDate) {
            $q->whereDate("created_at", ">=", $fromDate)->whereDate("created_at", "<=", $toDate);
        })->get();
        $grossRevenue = $orders->where('status', '<>', 'Cancelled')->sum('total');
        $order_commission_amount = $orders->where('status', '<>', 'Cancelled')->sum('order_commission_amount');
        $insuranceRevenue = $orders->where('security_option', 'insurance')->where('status', '<>', 'Cancelled')->sum('security_option_amount');
        $netRevenue = $order_commission_amount + $insuranceRevenue;
        $lineChartCategories = [];
        $lineChartData = [];
        $lineChartTitle = 'Months';
        $lineChartOrders = Order::select('*', DB::raw('MONTH(created_at) as month'), DB::raw('count(id) as total_order'), DB::raw('sum(total) as order_amount'), 'order_date')->when(!is_null($fromDate) && !is_null($toDate), function ($q) use ($fromDate, $toDate) {
            $q->whereDate("created_at", ">=", $fromDate)->whereDate("created_at", "<=", $toDate);
        })->groupBy('month')->get()->keyBy('month')->toArray();

        foreach ($lineChartOrders as $lineChartOrder) {
            $totalOrderValue = number_format((float)$lineChartOrder['order_amount'], '2', '.', '');
            array_push($lineChartCategories, date($format, strtotime($lineChartOrder['order_date'])));
            array_push($lineChartData, $totalOrderValue);

            $revenueChartDetails["months"][] = date($format, strtotime($lineChartOrder['order_date']));
            $revenueChartDetails["totalOrders"][] =  $lineChartOrder['total_order'];
        }

        return view('admin.dashboard', compact('orders', 'users', 'revenueChartDetails', 'totalOrderAmount', 'grossRevenue', 'netRevenue', 'lineChartCategories', 'lineChartData', 'lineChartTitle', 'order_commission_amount', 'products'));
    }

    public function commission(Request $request)
    {
        if ($request->method() == "POST") {
            // dd('herere', $request);
            // $request->validate([
            //     "order_commission" => "required|min:1",
            //     "order_commission_type" => "required|in:Fixed,Percentage",
            //     "customer_transaction_fee" => "required|min:1",
            //     "customer_transaction_fee_type" => "required|in:Fixed,Percentage",
            //     "insurance_fee" => "required|min:1",
            //     "insurance_fee_type" => "required|in:Fixed,Percentage",
            //     "security_fee" => "required|min:1",
            //     "security_fee_type" => "required|in:Fixed,Percentage",
            // ]);

            $formRequest = $request->except(["_token", "global_dateformat", "global_timeformat", "global_datetimeformat", "global_jquery_dateformat", "global_pagination"]);
            foreach ($formRequest as $key => $value) {
                AdminSetting::where('key', $key)->update(['value' => $value, 'type' => $request->input($key . '_type')]);
            }
            return redirect()->route("admin.commission")->with("success", "Commission updated successfully.");
        }

        $adminSettings = AdminSetting::where('key', '!=', 'renter_transaction_fee')->get();

        return view('admin.commission.commission', compact('adminSettings'));
    }

    public function cms()
    {
        $pages = CmsPage::get();

        return view('admin.cms.cms_list', compact('pages'));
    }

    public function editCms(CmsPage $page)
    {
        return view('admin.cms.cms_edit', compact('page'));
    }
    public function transaction()
    {
        $orders = Order::with('transaction', 'retailer', 'user')->get();

        return view('admin.transaction', compact('orders'));
    }
    public function saveCms(Request $request, CmsPage $page)
    {
        $request->validate([
            'title' => 'required',
            // 'tag_line' => 'required',
            // 'content' => 'required',
        ], [
            'title.required' => 'Please enter the title',
            // 'tag_line.required' => 'Please enter the tag line',
            // 'content.required' => 'Please enter the content',
        ]);
        if ($request->contact) {
            $content = json_encode($request->contact);
        } else {
            $content = $request->content;
        }
        $page->update([
            'title' => $request->title,
            'tag_line' => $request->tag_line,
            'content' => $content,
            'display_text' => $request->display_text,
            'url' => $request->url,
        ]);

        return redirect()->route('admin.cms')->with('success', 'Page updated successfully');
    }

    public function payToRetailer(Request $request, User $retailer)
    {
        // dd($request->all());
        $retailer->load(["vendorBankDetails"]);
        if ($retailer->vendorBankDetails->is_verified == "Yes" && !is_null($retailer->vendorBankDetails->stripe_account_token)) {
            try {
                $orderDetails = Order::whereIn("id", explode(",", $request->order_id))->get();
                /*TRANSFER AMOUNT*/
                $transferAmount = $orderDetails->sum("vendor_received_amount") * 100;
                $payoutData = [
                    "amount" => $transferAmount,
                    "currency" => "usd",
                    "destination" => jsdecode_userdata($retailer->vendorBankDetails->stripe_account_token),
                    "metadata" => [
                        "order_ids" => $request->order_id
                    ]
                ];
                $order_id = explode(",", $request->order_id);

                $orders = Order::with('transaction')->whereIn('id', $order_id)->first();

                $transfer = $this->stripeClient->transfers->create([
                    $payoutData
                ]);

                $retaileramount = RetailerPayout::create([
                    "retailer_id" => $retailer->id,
                    "transaction_id" => $transfer['id'],
                    "order_id" => implode(',', $orderDetails->pluck("id")->toArray()),
                    "amount" => $transfer['amount'] / 100,
                    "gateway_response" => str_replace("Stripe\Transfer JSON: ", "", $transfer)

                ]);
                // $user = auth()->user();
                // dd($retailer, $orders);
                $order_item = OrderItem::where('order_id', $orders->id)->first();
                $emaildata = [
                    'orderitem' => $order_item,
                    'orders' => $orders,
                ];

                if (@$retailer->notification->payment == 'on') {
                    $retailer->notify(new Payment($emaildata, $retailer));
                }

                return back()->with("success", __('admin.payount_successfull'));
            } catch (Exception $e) {
                return back()->with("error", $e->getMessage());
            }
        }
    }

    public function retailerPayouts(Request $request)
    {
        $keyword = @$request->search;
        $retailerPayouts = RetailerPayout::with("retailerDetails")->when(!is_null($keyword), function ($q) use ($keyword) {
            $q->whereHas('retailerDetails', function ($q) use ($keyword) {
                $q->where("email", "like", strtolower($keyword) . "%");
            });
        })->paginate($request->global_pagination);

        return view('admin.retailer_payouts', compact('retailerPayouts'));
    }

    public function disputedPayouts(Request $request)
    {
        $keyword = @$request->search;
        $customerPayouts = DisputeOrder::with("order.user")->when(!is_null($keyword), function ($q) use ($keyword) {
            $q->whereHas('order.user', function ($q) use ($keyword) {
                $q->where("email", "like", strtolower($keyword) . "%");
            });
        })->paginate($request->global_pagination);

        return view('admin.disputed_payouts', compact('customerPayouts'));
    }

    public function orders(Request $request)
    {
        $ordersQuery = Order::with(["user", "transaction", "retailer"])
            ->when(!is_null($request->customer), function ($q) use ($request) {
                $q->whereHas('user', function ($q) use ($request) {
                    $q->where("email", "like", strtolower($request->customer) . "%");
                });
            })
            ->when(!is_null($request->transaction), function ($q) use ($request) {
                $q->whereHas('transaction', function ($q) use ($request) {
                    $q->where("payment_id", "like", strtolower($request->transaction) . "%");
                });
            })
            ->when(!is_null($request->retailer), function ($q) use ($request) {
                $q->whereHas('item.retailer', function ($q) use ($request) {
                    $q->where("email", "like", strtolower($request->retailer) . "%");
                });
            })
            ->when(!is_null($request->order), function ($q) use ($request) {
                $q->where('id', $request->order);
            })
            ->when(!is_null($request->status) && $request->status != 'all', function ($q) use ($request) {
                $q->where('status', $request->status);
            });

        // Conditionally add where clause if the column exists
        if (Schema::hasColumn('orders', 'dispute_status')) {
            $ordersQuery->where('dispute_status', 'No');
        }

        $orders = $ordersQuery->paginate($request->global_pagination);
        $title = "All Orders";

        return view('admin.orders', compact('orders', 'title'));
    }


    public function disputedOrders(Request $request)
    {
        $ordersQuery = Order::with(["user", "transaction", "item.retailer"])
            ->when(!is_null($request->customer), function ($q) use ($request) {
                $q->whereHas('user', function ($q) use ($request) {
                    $q->where("email", "like", strtolower($request->customer) . "%");
                });
            })
            ->when(!is_null($request->transaction), function ($q) use ($request) {
                $q->whereHas('transaction', function ($q) use ($request) {
                    $q->where("payment_id", "like", strtolower($request->transaction) . "%");
                });
            })
            ->when(!is_null($request->retailer), function ($q) use ($request) {
                $q->whereHas('item.retailer', function ($q) use ($request) {
                    $q->where("email", "like", strtolower($request->retailer) . "%");
                });
            })
            ->when(!is_null($request->order), function ($q) use ($request) {
                $q->where('id', $request->order);
            });

        // Conditionally add where clause if the column exists
        if (Schema::hasColumn('orders', 'dispute_status')) {
            $ordersQuery->where('dispute_status', 'Yes');
        }

        $orders = $ordersQuery->paginate($request->global_pagination);

        $title = "Disputed Orders";

        return view('admin.orders', compact('orders', 'title'));
    }


    public function viewOrder(Request $request, Order $order)
    {
        $order->load(["user", "transaction", "item.retailer", "disputeDetails"]);

        return view('admin.view_orders', compact('order'));
    }

    public function upload(Request $request)
    {
        $fileName = $request->file('file')->getClientOriginalName();
        $path = $request->file('file')->storeAs('cms', $fileName, 'public');

        return response()->json(['location' => "/storage/{$path}"]);
    }

    public function resolveDisputeOrder(Request $request, Order $order)
    {
        $status = ['Pending', 'Picked Up'];
        if (!in_array($order->status, $status)) {
            return back()->with('error', 'This action cannot be performed.');
        }

        $order->load(['user', 'transaction', 'item.retailer', 'disputeDetails']);
        // dd("refund", $request, $order->toArray());
        $dateTime = date('Y-m-d H:i:s');
        $message = 'Dispute for the order has been resolved.';
        // $orderItemData = [
        //     'status' => $request->resolved_status,
        //     'dispute_status' => 'Resolved'
        // ];
        $disputedOrderData = [
            "refund_amount" => 0,
            'status'=>$request->status,
            'resolved_status' => $request->resolved_status,
            'resolved_date' => $dateTime,
        ];
        if ('Picked Up' == $order->status && 'Completed' == $request->resolved_status) {

            /*RETRIEVE PAYMENT INTENT DETAILS*/
            $paymentIntentData = $this->stripeClient->paymentIntents->retrieve($order->transaction->payment_id);
            /*REFUND PAYMENT*/
            try {
                $refundResponse = $this->stripeClient->refunds->create([
                    'charge' => $paymentIntentData->latest_charge,
                ]);
            } catch (Exception $e) {
                return back()->with("success", str_replace("Charge " . $paymentIntentData->latest_charge, "Order ", $e->getMessage()));
            }

            if ($refundResponse->status == "succeeded") {
                $disputedOrderData['transaction_id'] = $refundResponse['id'];
                $disputedOrderData['refund_type'] = 'Full';
                $disputedOrderData['refund_amount'] = $order->total;
                $disputedOrderData['gateway_response'] = str_replace("Stripe\Refund JSON: ", "", $refundResponse);
                $message = 'Refund has been successfully processed. The amount will credit to customer card with in 5-7 business days.';
            }
        }
        /*UPDATE ORDER*/
        // Order::where('id', $order->id)->update($orderItemData);
        // OrderItem::where('order_id', $order->id)->update($orderItemData);
        DisputeOrder::where('id', $order->disputeDetails->id)->update($disputedOrderData);
        ProductUnavailability::where('order_id', $order->id)->delete();
        // $data = [
        //     [
        //         'order_id' => $order->id,
        //         'sender_id' => auth()->user()->id,
        //         'receiver_id' => $order->item->retailer->id,
        //         'action_type' => 'Order Dispute Resolved',
        //         'created_at' => $dateTime,
        //         'message' => 'Dispute for the order #' . $order->id . ' has been resolved by the site administrator.'
        //     ],
        //     [
        //         'order_id' => $order->id,
        //         'sender_id' => auth()->user()->id,
        //         'receiver_id' => $order->user_id,
        //         'action_type' => 'Order Dispute Resolved',
        //         'created_at' => $dateTime,
        //         'message' => 'Dispute for the order #' . $order->id . ' has been resolved by the site administrator.'
        //     ]
        // ];
        // $this->sendNotification($data);

        return redirect()->back()->with('success', $message);
    }

    public function viewCustomerCompletedOrders(Request $request, User $customer)
    {
        // Loading customer related data
        $customer->load(["vendorBankDetails", "vendorCompletedOrderedItems.order", "vendorPayout"]);

        // Fetching orders with conditions and pagination
        $ordersQuery = Order::with(['refundSecurityDetails'])
            ->where('user_id', $customer->id)
            ->where('status', 'Completed');

        // Conditionally add where clauses if the columns exist
        if (Schema::hasColumn('orders', 'dispute_status')) {
            $ordersQuery->where(function ($query) {
                $query->where('dispute_status', 'No')
                    ->orWhereNull('dispute_status');
            });
        }

        if (Schema::hasColumn('orders', 'security_option')) {
            $ordersQuery->where(function ($query) {
                $query->where('security_option', 'security')
                    ->orWhereNull('security_option');
            });
        }

        $orders = $ordersQuery->paginate($request->global_pagination);

        // Filtering orders where refundSecurityDetails is null
        $orders = $orders->filter(function ($value, $key) {
            return is_null($value->refundSecurityDetails);
        });

        // Getting paid order IDs
        $paidOrderIds = [];
        if ($customer->vendorPayout->isNotEmpty()) {
            foreach ($customer->vendorPayout->pluck("order_id")->toArray() as $orderIds) {
                if (is_array($orderIds) && count($orderIds) > 1) {
                    foreach ($orderIds as $id) {
                        $paidOrderIds[] = $id;
                    }
                } else {
                    $paidOrderIds[] = $orderIds[0];
                }
            }
        }

        // Returning the view with necessary data
        return view('admin.customer.view_customer_completed_orders', compact('customer', 'orders', 'paidOrderIds'));
    }

    public function viewRetailerCompletedOrders(Request $request, User $retailer)
    {
        $retailer->load(["vendorBankDetails", "vendorCompletedOrderedItems.order", "vendorPayout"]);
        // dd($retailer);
        $paidOrderIds = [];
        if ($retailer->vendorPayout->isNotEmpty()) {
            foreach ($retailer->vendorPayout->pluck("order_id")->toArray() as $orderIds) {
                if (count($orderIds) > 1) {
                    foreach ($orderIds as $id) {
                        $paidOrderIds[] = $id;
                    }
                } else {
                    $paidOrderIds[] = $orderIds[0];
                }
            }
        }
        return view('admin.retailer.view_retailer_completed_orders', compact('retailer', 'paidOrderIds'));
    }

    public function paySecurityToCustomer(Request $request, User $customer)
    {
        // dd($request->toArray(), $customer);
        try {
            $request->validate([
                "order_id" => "required"
            ]);

            $explodeOrders = explode(",", $request->order_id);
            foreach ($explodeOrders as $explodeOrder) {
                $getOrderDetail = Order::with("transaction")->findOrFail($explodeOrder);
                $checkIfSecurityIsProcessed = RefundSecurity::where("order_id", $getOrderDetail->id)->first();
                if (!is_null($checkIfSecurityIsProcessed)) {
                    return back()->with("error", 'Security for Order #' . $getOrderDetail->id . ' has been already processed.');
                }

                /*RETRIEVE PAYMENT INTENT DETAILS*/
                $paymentIntentData = $this->stripeClient->paymentIntents->retrieve(
                    $getOrderDetail->transaction->payment_id
                );

                /*REFUND SECURITY PAYMENT*/
                try {
                    $refundResponse = $this->stripeClient->refunds->create([
                        'charge' => $paymentIntentData->latest_charge,
                        'amount' => $getOrderDetail->security_option_amount * 100
                    ]);
                } catch (Exception $e) {
                    return back()->with("error", str_replace("Charge " . $paymentIntentData->latest_charge, "Order ", $e->getMessage()));
                }

                RefundSecurity::create([
                    "user_id" => $getOrderDetail->user_id,
                    "product_id" => $getOrderDetail->item->product_id,
                    "order_id" => $getOrderDetail->id,
                    "transaction_id" => $refundResponse['id'],
                    "security_return_date" => date('Y-m-d H:i:s'),
                    "gateway_response" => str_replace("Stripe\Refund JSON: ", "", $refundResponse),
                    "security_amount" => $getOrderDetail->security_option_amount,
                    "paid_amount" => $refundResponse['amount'] / 100,
                    "status" => "Yes"
                ]);

                $data = [
                    [
                        'order_id' => $getOrderDetail->id,
                        'sender_id' => auth()->user()->id,
                        'receiver_id' => $getOrderDetail->user_id,
                        'action_type' => 'Security Returned',
                        'created_at' => date("Y-m-d H:i:s"),
                        'message' => 'Security for Order #' . $getOrderDetail->id . ' has been processed. Amount will Credit to your Card with in 5-7 business days.'
                    ]
                ];
                $this->sendNotification($data);
            }

            return redirect()->back()->with("success", "Security amount has been processed. The amount will credit to customer source card within 5-7 business days.");
        } catch (Exception $e) {
            return redirect()->back()->with("error", $e->getMessage());
        }
    }


    public function securityPayouts(Request $request)
    {
        $keyword = @$request->search;
        $securityPayouts = RefundSecurity::with("order.user")->when(!is_null($keyword), function ($q) use ($keyword) {
            $q->whereHas('order.user', function ($q) use ($keyword) {
                $q->where("email", "like", strtolower($keyword) . "%");
            });
        })->paginate($request->global_pagination);

        return view('admin.security_payouts', compact('securityPayouts'));
    }

    public function payouts_export(Request $request)
    {

        if ($request->security) {

            return Excel::download(new PayoutsExport($request->security), 'payoutsexport.xlsx');
        } elseif ($request->dispute) {

            return Excel::download(new PayoutsExport($request->dispute), 'payoutsexport.xlsx');
        }

        return Excel::download(new PayoutsExport(), 'payoutsexport.xlsx');
    }
}
