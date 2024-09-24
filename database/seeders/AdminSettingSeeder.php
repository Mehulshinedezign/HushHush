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
            "order_commission" => [
                "value" => "1",
                "type" => "Percentage",
            ],
            "identity_commission" => [
                "value" => "1",
                "type" => "fixed",
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
