<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            [
                'title' => 'Date Format',
                'key'   => 'global_date_format_for_check',
                'value' => 'Y-m-d',
                'hidden' => 'No',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'title' => 'Date Format',
                'key'   => 'global_date_format',
                'value' => 'd/m/Y',
                'hidden' => 'No',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'title' => 'Time Format',
                'key'   => 'global_time_format',
                'value' => 'H:i:a',
                'hidden' => 'No',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'title' => 'Date Time Format',
                'key'   => 'global_date_time_format',
                'value' => 'Y-m-d H:i:a',
                'hidden' => 'No',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'title' => 'Calender Date Format',
                'key'   =>  'global_js_date_format',
                'value' =>  'MM/DD/YYYY',
                'hidden' => 'Yes',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'title' => 'Date Separator',
                'key'   =>  'global_date_separator',
                'value' =>  '-',
                'hidden' => 'No',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'title' => 'Pagination',
                'key'   =>  'global_pagination',
                'value' =>  '10',
                'hidden' => 'No',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'title' => 'Product Pagination',
                'key'   =>  'global_product_pagination',
                'value' =>  '24',
                'hidden' => 'No',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'title' => 'Home Page Title',
                'key'   =>  'global_home_page_title',
                'value' =>  'Add your text',
                'hidden' => 'No',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'title' => 'Footer Page Content',
                'key'   =>  'global_footer_page_content',
                'value' =>  'Add your text',
                'hidden' => 'No',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'title' => 'Product Max Image Count',
                'key'   =>  'global_max_product_image_count',
                'value' =>  '10',
                'hidden' => 'Yes',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'title' => 'Product Min Image Count',
                'key'   =>  'global_min_product_image_count',
                'value' =>  '2',
                'hidden' => 'Yes',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'title' => 'Order Max Picked Up Image Count',
                'key'   =>  'global_max_picked_up_image_count',
                'value' =>  '5',
                'hidden' => 'Yes',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'title' => 'Order Max Returned Image Count',
                'key'   =>  'global_max_returned_image_count',
                'value' =>  '5',
                'hidden' => 'Yes',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'title' => 'Order Min Picked Up Image Count',
                'key'   =>  'global_min_picked_up_image_count',
                'value' =>  '2',
                'hidden' => 'Yes',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'title' => 'Order Min Returned Image Count',
                'key'   =>  'global_min_returned_image_count',
                'value' =>  '2',
                'hidden' => 'Yes',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'title' => 'Dispute Max Image Count',
                'key'   =>  'global_max_dispute_image_count',
                'value' =>  '5',
                'hidden' => 'Yes',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'title' => 'Dispute Min Image Count',
                'key'   =>  'global_min_dispute_image_count',
                'value' =>  '2',
                'hidden' => 'Yes',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'title' => 'PHP Image Extension',
                'key'   =>  'global_php_image_extension',
                'value' =>  'jpg,jpeg,png',
                'hidden' => 'Yes',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'title' => 'JS Image Extension',
                'key'   =>  'global_js_image_extension',
                'value' =>  "['image/jpeg', 'image/png', 'image/jpg']",
                'hidden' => 'Yes',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'title' => 'PHP Proof Extension',
                'key'   =>  'global_php_proof_extension',
                'value' =>  'jpg, jpeg, png',
                'hidden' => 'Yes',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'title' => 'JS Proof Extension',
                'key'   =>  'global_js_proof_extension',
                'value' =>  "['image/jpeg', 'image/png', 'image/jpg']",
                'hidden' => 'Yes',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'title' => 'JS File Size',
                'key'   =>  'global_js_file_size',
                'value' =>  '5000000',
                'hidden' => 'Yes',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'title' => 'PHP File Size',
                'key'   =>  'global_php_file_size',
                'value' =>  '5000',
                'hidden' => 'Yes',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'title' => 'Preview File Name Length',
                'key'   =>  'global_preview_file_name_length',
                'value' =>  '10',
                'hidden' => 'Yes',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'title' => 'Customer Booking Gap',
                'key'   =>  'global_customer_time_gap',
                'value' =>  '30',
                'hidden' => 'Yes',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'title' => 'Calendar Time Gap',
                'key'   =>  'global_calendar_time_gap',
                'value' =>  '5',
                'hidden' => 'Yes',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'title' => 'Calendar Js Date Time Format',
                'key'   =>  'global_js_date_time_format',
                'value' =>  'YYYY-MM-DD hh:mm A',
                'hidden' => 'Yes',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'title' => 'Product Date Time Format',
                'key'   =>  'global_product_date_time_format',
                'value' =>  'Y-m-d H:i A',
                'hidden' => 'Yes',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
            [
                'title' => 'Product Blade Date Time Format',
                'key'   =>  'global_product_blade_date_time_format',
                'value' =>  'Y-m-d h:i A',
                'hidden' => 'Yes',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
        ];

        Setting::insert($settings);
    }
}
