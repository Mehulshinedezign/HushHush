<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Brand Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the brand. You are free to 
    | change them to anything you want to customize your views to better 
    | match your application.
    |
    */

    'title'   =>  'Brand',
    'name'    =>  'Brand Name',
    'addBrand' =>  'Add Brand',
    'editBrand' =>  'Edit Brand',
    'viewBrand' =>  'View Brand',
    'empty'    => 'Brand not available',

    'fields'    =>  [
        'brand_name'   =>  'Brand Name',
        'status' =>  'Status',
    ],
    'placeholders' => [
        'brand_name' => 'Enter the brand name', 
    ],
    'validations'    =>  [
        'brand_name'       =>  'Please enter the brand name.',
        'nameString' =>  'Brand name must be a string.',
    ],
    'messages' => [
        'saveBrand'   => 'Brand saved successfully.',
        'updateBrand' => 'Brand updated successfully.',
        'deleteBrand' => 'Brand deleted successfully.',
    ],
];
