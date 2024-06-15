<?php

namespace App\Http\Controllers\Retailer;

use App\Exports\PayoutsHistoryExport;
use App\Http\Controllers\Controller;
use App\Models\{Notification, OrderItem, Product, RetailerPayout};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use Maatwebsite\Excel\Facades\Excel;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $fromDate = date('Y-01-01');
        $toDate = date('Y-m-d');
        $fromDate = $toDate = null;
        $retailerId = auth()->user()->id;
        $fromAndToDate = !is_null($request->date) ? array_map('trim', explode($request->global_date_separator, $request->date)) : [];
        if (count($fromAndToDate) == 2) {
            $fromDate = DateTime::createFromFormat($request->global_date_format_for_check, $fromAndToDate[0])->format('Y-m-d');
            $toDate = DateTime::createFromFormat($request->global_date_format_for_check, $fromAndToDate[1])->format('Y-m-d');
        }
        // get the total products
        $products = Product::select('status', 'created_at')->where('user_id', $retailerId)->when(!is_null($fromDate) && !is_null($toDate), function ($q) use ($fromDate, $toDate) {
            $q->whereDate("created_at", ">=", $fromDate)->whereDate("created_at", "<=", $toDate);
        })->get();
        $totalProductsCount = $products->count();
        $activeProductsCount = $products->where('status', 'Active')->count();
        $InactiveProductsCount = $products->where('status', 'Inactive')->count();

        // get the customers
        $totalCustomersCount = OrderItem::where('retailer_id', $retailerId)->when(!is_null($fromDate) && !is_null($toDate), function ($q) use ($fromDate, $toDate) {
            $q->whereDate("date", ">=", $fromDate)->whereDate("date", "<=", $toDate);
        })->get()->groupBy('customer_id')->count();
        $currentMonthCustomersCount = OrderItem::where('retailer_id', $retailerId)->whereMonth('date', date('m'))->get()->groupBy('customer_id')->count();

        // get the revenue
        $where = [
            ['retailer_id', '=', $retailerId],
            ['status', '<>', 'Cancelled'],
            ['dispute_status', '<>', 'Yes'],
        ];
        $totalOrderRevenue = OrderItem::where($where)->when(!is_null($fromDate) && !is_null($toDate), function ($q) use ($fromDate, $toDate) {
            $q->whereDate("date", ">=", $fromDate)->whereDate("date", "<=", $toDate);
        })->sum('total');
        $currentMonthOrderRevenue = OrderItem::where($where)->whereMonth('date', date('m'))->sum('total');
        $totalRevenue = OrderItem::where($where)->when(!is_null($fromDate) && !is_null($toDate), function ($q) use ($fromDate, $toDate) {
            $q->whereDate("date", ">=", $fromDate)->whereDate("date", "<=", $toDate);
        })->sum('vendor_received_amount');
        $currentMonthRevenue = OrderItem::where($where)->whereMonth('date', date('m'))->sum('vendor_received_amount');
        $receivedPayout = RetailerPayout::where('retailer_id', $retailerId)->when(!is_null($fromDate) && !is_null($toDate), function ($q) use ($fromDate, $toDate) {
            $q->whereDate("created_at", ">=", $fromDate)->whereDate("created_at", "<=", $toDate);
        })->sum('amount');
        $currentMonthReceivedPayout = RetailerPayout::where('retailer_id', $retailerId)->whereMonth('created_at', date('m'))->sum('amount');
        $retailerPayouts = RetailerPayout::select(DB::raw('MONTH(created_at) as month'), DB::raw('sum(amount) as total'), 'retailer_id')->where('retailer_id', $retailerId)->when(!is_null($fromDate) && !is_null($toDate), function ($q) use ($fromDate, $toDate) {
            $q->whereDate("created_at", ">=", $fromDate)->whereDate("created_at", "<=", $toDate);
        })->groupBy('month')->get()->keyBy('month')->toArray();
        $pendingPayout = $totalRevenue - $receivedPayout;
        $revenueChart = [];
        for ($i = 1; $i <= 12; $i++) {
            if (in_array($i, array_keys($retailerPayouts))) {
                array_push($revenueChart, floatval($retailerPayouts[$i]['total']));
            } else {
                array_push($revenueChart, 0);
            }
        }

        // get the orders
        $totalOrdersCount = OrderItem::where('retailer_id', $retailerId)->when(!is_null($fromDate) && !is_null($toDate), function ($q) use ($fromDate, $toDate) {
            $q->whereDate("date", ">=", $fromDate)->whereDate("date", "<=", $toDate);
        })->count();
        $currentMonthOrderCount = OrderItem::where('retailer_id', $retailerId)->whereMonth('date', date('m'))->count();
        $recentOrders = OrderItem::with('order')->where('retailer_id', $retailerId)->when(!is_null($fromDate) && !is_null($toDate), function ($q) use ($fromDate, $toDate) {
            $q->whereDate("date", ">=", $fromDate)->whereDate("date", "<=", $toDate);
        })->take('5')->orderBy('id', 'DESC')->get();

        // get the orders line chart
        $lineChartCategories = [];
        $lineChartData = [];
        $orders = OrderItem::select(DB::raw('MONTH(date) as month'), DB::raw('count(order_id) as total_order'), 'date', 'retailer_id')->where('retailer_id', $retailerId)->when(!is_null($fromDate) && !is_null($toDate), function ($q) use ($fromDate, $toDate) {
            $q->whereDate("date", ">=", $fromDate)->whereDate("date", "<=", $toDate);
        })->groupBy('month')->get()->keyBy('month')->toArray();
        $lineChartTitle = 'Months';
        foreach ($orders as $order) {
            array_push($lineChartCategories, date('M Y', strtotime($order['date'])));
            array_push($lineChartData, $order['total_order']);
        }

        // get the recent notifications
        $recentNotifications = Notification::with('order.item.product.thumbnailImage')->where('receiver_id', $retailerId)->when(!is_null($fromDate) && !is_null($toDate), function ($q) use ($fromDate, $toDate) {
            $q->whereDate("created_at", ">=", $fromDate)->whereDate("created_at", "<=", $toDate);
        })->orderByDesc('id')->take('7')->get();

        // Pie Chart
        $waitingOrder = OrderItem::where('retailer_id', $retailerId)->when(!is_null($fromDate) && !is_null($toDate), function ($q) use ($fromDate, $toDate) {
            $q->whereDate("date", ">=", $fromDate)->whereDate("date", "<=", $toDate);
        })->where('dispute_status', '<>', 'Yes')->where('status', 'Pending')->count();
        $completedOrder = OrderItem::where('retailer_id', $retailerId)->when(!is_null($fromDate) && !is_null($toDate), function ($q) use ($fromDate, $toDate) {
            $q->whereDate("date", ">=", $fromDate)->whereDate("date", "<=", $toDate);
        })->where('dispute_status', '<>', 'Yes')->where('status', 'Completed')->count();
        $pickedUpOrder = OrderItem::where('retailer_id', $retailerId)->when(!is_null($fromDate) && !is_null($toDate), function ($q) use ($fromDate, $toDate) {
            $q->whereDate("date", ">=", $fromDate)->whereDate("date", "<=", $toDate);
        })->where('dispute_status', '<>', 'Yes')->where('status', 'Picked Up')->count();
        $cancelledOrder = OrderItem::where('retailer_id', $retailerId)->when(!is_null($fromDate) && !is_null($toDate), function ($q) use ($fromDate, $toDate) {
            $q->whereDate("date", ">=", $fromDate)->whereDate("date", "<=", $toDate);
        })->where('dispute_status', '<>', 'Yes')->where('status', 'Cancelled')->count();
        $disputedOrder = OrderItem::where('retailer_id', $retailerId)->when(!is_null($fromDate) && !is_null($toDate), function ($q) use ($fromDate, $toDate) {
            $q->whereDate("date", ">=", $fromDate)->whereDate("date", "<=", $toDate);
        })->where('dispute_status', 'Yes')->count();
        $pieChart = [$waitingOrder, $completedOrder, $pickedUpOrder, $cancelledOrder, $disputedOrder];

        $data = [
            'totalProductsCount' => $totalProductsCount,
            'activeProductsCount' => $activeProductsCount,
            'InactiveProductsCount' => $InactiveProductsCount,
            'totalOrdersCount' => $totalOrdersCount,
            'currentMonthOrderCount' => $currentMonthOrderCount,
            'totalOrderRevenue' => number_format((float)$totalOrderRevenue, 2, '.', ''),
            'currentMonthOrderRevenue' => number_format((float)$currentMonthOrderRevenue, 2, '.', ''),
            'totalRevenue' => number_format((float)$totalRevenue, 2, '.', ''),
            'currentMonthRevenue' => number_format((float)$currentMonthRevenue, 2, '.', ''),
            'receivedPayout' => number_format((float)$receivedPayout, 2, '.', ''),
            'currentMonthReceivedPayout' => number_format((float)$currentMonthReceivedPayout, 2, '.', ''),
            'pendingPayout' => number_format((float)$pendingPayout, 2, '.', ''),
            'totalCustomersCount' => $totalCustomersCount,
            'currentMonthCustomersCount' => $currentMonthCustomersCount,
            'recentOrders' => $recentOrders,
            'recentNotifications' => $recentNotifications,
            'pieChart' => $pieChart,
            'revenueChart' => $revenueChart,
            'lineChartData' => $lineChartData,
            'lineChartMaxData' => count($lineChartData) ? max($lineChartData) : 0,
            'lineChartCategories' => $lineChartCategories,
            'lineChartTitle' => $lineChartTitle,
            'completedOrder' => $completedOrder,
            'totalactivebooking' => $pickedUpOrder,
        ];

        return view('retailer.dashboard', compact('data'));
    }

    public function payouts(Request $request)
    {
        $fromDate = $toDate = null;
        $fromAndToDate = !is_null($request->date) ? array_map('trim', explode($request->global_date_separator, $request->date)) : [];
        if (count($fromAndToDate) == 2) {
            $fromDate = DateTime::createFromFormat($request->global_date_format_for_check, $fromAndToDate[0])->format('Y-m-d');
            $toDate = DateTime::createFromFormat($request->global_date_format_for_check, $fromAndToDate[1])->format('Y-m-d');
        }

        $transactions = RetailerPayout::where('retailer_id', auth()->user()->id)->when(!is_null($request->search), function ($q) use ($request) {
            $q->where("transaction_id", "like", strtolower($request->search) . "%");
        })->when(!is_null($fromDate) && !is_null($toDate), function ($q) use ($fromDate, $toDate) {
            $q->whereDate('created_at', '>=', $fromDate);
            $q->whereDate('created_at', '<=', $toDate);
        })->paginate($request->global_pagination);

        // dd($transactions->toArray());

        return view('retailer.payouts', compact('transactions'));
    }

    public function payouts_list()
    {
        return Excel::download(new PayoutsHistoryExport, 'payoutshistoryexport.xlsx');
    }
}
