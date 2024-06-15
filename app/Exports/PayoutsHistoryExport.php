<?php

namespace App\Exports;

use App\Models\RetailerPayout;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\FromQuery;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\FromCollection;

class PayoutsHistoryExport implements WithCustomCsvSettings ,WithHeadings , WithMapping , FromQuery, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ','
        ];
    }
    public function headings(): array
    {
        return [
            'Order Id',
            'Transaction Id',
            'Amount',
            'Date',
        ];
    }

    public function map($transactions): array
    {
        // dd($transactions->toArray());
        return [
            implode(" ", $transactions->order_id),
            $transactions->transaction_id,
            $transactions->amount,
            date('m/d/Y h:i A', strtotime($transactions->created_at)),
           

        ];
    }

    public function query()
    {
        $transactions = RetailerPayout::where('retailer_id', auth()->user()->id);
        //dd($transactions);
        return $transactions;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
   
   
}
