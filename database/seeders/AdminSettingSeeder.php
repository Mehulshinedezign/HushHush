<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminSetting;

class AdminSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $keys = [
            "renter_transaction_fee" => [
                "value" => "0",
                "type" => "Percentage",
            ],
            "order_commission" => [
                "value" => "15",
                "type" => "Percentage",
            ],
            // "insurance_fee" => [
            //     "value" => "25",
            //     "type" => "Percentage",
            // ],
            // "security_fee" => [
            //     "value" => "60",
            //     "type" => "Percentage",
            // ]
        ];

        foreach ($keys as $key => $value) {
            AdminSetting::updateOrCreate(
                [
                    "key" => $key
                ],
                [
                    "value" => $value["value"],
                    "type" => $value["type"],
                ]
            );
        }
    }
}
