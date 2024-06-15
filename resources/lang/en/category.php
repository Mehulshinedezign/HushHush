<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Category Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the category. You are free to 
    | change them to anything you want to customize your views to better 
    | match your application.
    |
    */

    'title'   =>  'Category',
    'name'   =>  'Name',
    'addCategory'   =>  'Add Category',
    'editCategory'   =>  'Edit Category',
    'viewCategory'   =>  'View Category',
    'empty' => 'Category not available',
    'noOfProducts' => 'No. of Products',
    'fields'    =>  [
        'name'   =>  'Name',
        'status'   =>  'Status',
    ],
    'placeholders' => [
        'name' => 'Enter the category name',
    ],
    'validations'    =>  [
        'name'   =>  'Please enter the category name.',
        'nameString'    =>  'Category name must be a string.',
        'image'   =>  'Please select category icon.',
        'type'   =>  'Please select size type.',
    ],
    'messages' => [
        'saveCategory' => 'Category saved successfully.',
        'updateCategory' => 'Category updated successfully.',
        'deleteCategory' => 'Category deleted successfully.',
    ]
];
