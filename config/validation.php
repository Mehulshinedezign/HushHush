<?php
return [
        'name_regex' =>'/^[^-\s][a-zA-Z\s]{1,50}$/',
        'name_regex_message' =>  '/^[a-zA-Z]+[a-zA-Z\s]{1,50}$/',
        'name_minlength' => 2,
        'name_maxlength' => 50,

        'phone_minlength' => 10,
        'phone_maxlength' => 10,

        'descriptionMaxLength' => 1000,


        'first_name_regex' =>'/^[a-zA-Z]{1,30}$/',
        'first_name_regex_message' =>  'Only alphabets allowed',
        'first_name_minlength' => 2,
        'first_name_maxlength' => 30,

        'last_name_regex' =>'/^[a-zA-Z]{1,30}$/',
        'last_name_regex_message' =>  'Only alphabets allowed',
        'last_name_minlength' => 2,
        'last_name_maxlength' => 30,

        'location_regex' => '/^[a-zA-Z]+[a-zA-Z\s\d@#&:,]{1,50}$/',
        'location_regex_message' =>  '/^[a-zA-Z]+[a-zA-Z\s\d@#&:,.]{1,50}$/',
        'location_minlength' => 2,
        'location_maxlength' => 50,

        'php_profile_pic_mimes' => 'jpg,jpeg,png',
        'js_profile_pic_mimes' => 'jpg|jpeg|png',
        'php_profile_pic_size' => '2000',
        'php_profile_pic_size_user' => '10000',
        'js_profile_pic_size' => '2000000',
        'js_profile_pic_size_user' => '10000000',

        'php_csv_file_mimes' => 'csv',
        'js_csv_file_mimes' => 'jpg|jpeg|png',
        'php_csv_file_size' => '2000',
        'js_csv_file_size' => '2000000',

        'php_certificate_mimes' => 'pdf,jpeg,jpg,png',
        'js_certificate_mimes' => 'pdf,jpeg,jpg,png',
        'php_certificate_size' => '2000',
        'js_certificate_size' => '2000000',

        'email_regex' => '/^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i',
        'email_regex_message' =>  '/^[a-zA-Z]+(?!.*[\_\-\.]{2}).*[a-zA-Z0-9_\.\-]{2,}[a-zA-Z0-9]{1}@[a-zA-Z]+(\.[a-zA-Z]+)?[\.]{0}[a-zA-Z]{2,10}$/',

        'password_regex' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d!@#$%^&*]{8,32}$/',
        'password_regex_message' =>  '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d!@#$%^&*]{8,32}$/',
        'password_minlength' => 8,
        'password_maxlength' => 32,

        'php_media_mimes'  => 'jpeg,png,jpg,wmv,avi,mov,3gp,mp4',
        'js_media_mimes'  => 'jpeg|png|jpg|wmv|avi|mov|3gp|mp4',
        'js_media_name_max_length' => 80,
        'js_media_size' => 2000000,
        'php_media_size' => 2000,

        'username_regex' => '/^[a-zA-Z0-9]+([._]?[a-zA-Z0-9]+){1,50}$/',
        'media_mimes' => 'jpeg,png,jpg,wmv,avi,mov,3gp,mp4',

        'package_regex' =>'/^[^-\s][a-zA-Z\s]{10,100000}$/',
        'package_minlength' => 10,
        'package_maxlength' => 1000,

        'account_minlength' => 12,
        'account_maxlength' => 15,

        'routing_minlength' => 9,
        'routing_maxlength' => 12,
        'package_maxlength' => 100000,

        'price_minlength' => 1,
        'price_maxlength' => 99999.99,

        'price_regex' => '/^\d+(\.\d{1,2})?$/',

        // 'zipcode_regex' => '/^\d{5}(?:[-\s]\d{4})?$/',
        'zipcode_regex' => '/^[a-zA-Z0-9\s]{1,20}$/',

        'product_desc_minlength' => 8,
        'product_desc_maxlength' => 1000,

        'min_days_item_regex' => '/^[0-9]{1,20}$/',
        'min_days_item_regex_message' =>  'Only digits allowed',

        'min_complete_address' => 10,
        'max_complete_address' => 255,

        'php_gov_id_mimes' => 'jpg,jpeg,png,pdf',
        'js_gov_id_mimes' => 'jpg|jpeg|png|pdf',
        'php_gov_id_size' => '2000',
        'js_gov_id_size' => '2097152',
        'js_gov_id_size' => '2000000',

        'only_digit_regex' => '/^[0-9]{1,20}$/',
        'only_digit_regex_message' =>  'Only digits allowed',
];
