<?php

namespace App\Exports;

use App\Models\DisputeOrder;
use App\Models\RefundSecurity;
use App\Models\RetailerPayout;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\FromQuery;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PayoutsExport implements FromQuery , WithCustomCsvSettings, WithHeadings, WithMapping, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */

    function __construct($security = null)
    {
       
        $this->security = $security;
        // $this->dispute    = $dispute ;
    }
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ','
        ];
    }

    public function headings(): array
    {
       
        if($this->security == "securitypayout"){
            return ['Email', 'Transaction ID', 'Order ID', 'Order Amount', 'Security Amount', 'Security Amount Paid', 'Security Payout Processed', 'Status'];
        }elseif($this->security == "disputepayout"){
            return ['Email', 'Transaction ID', 'Order ID', 'Payout Amount', 'Dispute Date' , 'Resolve Date', 'Dispute Status'];
        }
        return [
           'Email',
           'Transaction Id',
           'Order Id',
           'Amount',
           'Date',
        ];
    }

    public function map($payouts = null): array
    {
        if(request()->get('security')== 'securitypayout'){
            return [
                strtolower($payouts->order->user->email) ,
                $payouts->transaction_id,
                "#". $payouts->order_id,
                $payouts->order->total,
                $payouts->order->security_option_amount,
                $payouts->paid_amount,
                date(request()->global_date_time_format, strtotime($payouts->security_return_date)),
                $payouts->status = "Yes" ? "Completed" : "Pending",
            ];
        }elseif(request()->get('dispute') == 'disputepayout'){
            return [
                strtolower($payouts->order->user->email),
                $payouts->transaction_id,
                '#'. $payouts->order_id,
                $payouts->refund_amount,
                date('m/d/Y h:i:a', strtotime($payouts->order->dispute_date)),
                is_null($payouts->resolved_date) ? "Not-resolved" : date('m/d/Y h:i:a', strtotime($payouts->resolved_date)),
                $payouts->order->dispute_status,
                
            ];
        } else {
            return [
                $payouts->retailerDetails->email,
                $payouts->transaction_id,
                implode(" ", $payouts->order_id),
                $payouts->amount,
                date('m/d/Y h:i A', strtotime($payouts->created_at)),

            ];
        }
    }

    public function query()
    {
       
        if($this->security == 'securitypayout'){
            $securityPayouts = RefundSecurity::with("order.user");
            return $securityPayouts;
        }elseif($this->security == 'disputepayout'){
            $customerPayouts = DisputeOrder::with("order.user");
            return $customerPayouts;
        }
        else{
            $retailerPayout = RetailerPayout::with("retailerDetails");
            return $retailerPayout;
        }

        
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
   
}
