<?php


return [
    'login' => [
        'email' => [
            'required' => __('This field is required.'),
            'email' => 'This is not a valid email address.',
            'regex' => 'Please enter the valid email address.',
        ],
        'password' => [
            'required' => __('This field is required.'),
            'min' => __('Password must be 8-32 characters and contain at least one of the following characters !@#$%^&*'),
            'max' => __('Password can be :min - :max characters.'),
            'regex' => 'Password can be alphanumeric and (@#$%^&*) these special characters *At least one uppercase *One lowercase *One numeric.',

        ],
    ],

    'change_password' => [
        'old_password' => [
            'required' => 'Please enter the old password.',
            'min' => 'Password can be :min - :max characters.',
            'max' => 'Password can be :min - :max characters.',
            'regex' => 'Enter a valid password.',

        ],
        'password' => [
            'required' => 'Please enter the new password.',
            'min' => 'Password can be :min - :max characters.',
            'max' => 'Password can be :min - :max characters.',
            'regex' => 'Password can be alphanumeric and (@#$%^&*) these special characters *At least one uppercase *One lowercase *One numeric.',

        ],
        'confirm_password' => [
            'required' => 'Please enter the confirm password.',
            'equal' => 'Confirm password not match.',

        ],
    ],

    'admin' => [
        'name' => [
            'required' => 'Please enter the name.',
            'min' => 'Name can be :min - :max characters.',
            'max' => 'Name can be :min - :max characters.',
            'regex' => 'Only alphabets and in between space are allowed.',
        ],
        'phone_number' => [
            'required' => 'Please enter the phone number.',
            'digits' => 'Phone number must be of :digits.',
            'phoneUS' => 'Phone number must be of :digits digits.',
        ],
        'email' => [
            'required' => 'Please enter your email.',
            'email' => 'This is not a valid email address.',
            'regex' => 'Please enter the valid email address.',
        ],
        'profile_pic' => [
            'required' => 'Please upload your profile picture.',
            'size' => 'File size should not be greater than 2 MB.',
            'mimes' => 'Supported only JPEG, JPG, PNG file type.'
        ],
    ],
    'admin_setting' => [
        'amount' => [
            'required' => 'Please enter the Amount.',
            'min' => 'Amount can be :min - :max characters.',
            'max' => 'Amount can be :min - :max characters.',
            'numeric' => 'Only numerics are allowed.',
        ],
        'commission_amount' => [
            'required' => 'Please enter the Amount.',
            'min' => 'Amount can be :min - :max characters.',
            'max' => 'Amount can be :min - :max characters.',
            'numeric' => 'Only numerics are allowed.',
        ],
    ],
    'profile' => [
        'name' => [
            'required' => 'Please enter the name.',
            'min' => 'Name can be :min - :max characters.',
            'max' => 'Name can be :min - :max characters.',
            'regex' => 'Only alphabets and in between space are allowed.',
        ],
        'email' => [
            'required' => 'Please enter your email.',
            'email' => 'This is not a valid email address.',
            'regex' => 'Please enter the valid email address.',
        ],
        'profile_pic' => [
            'required' => 'Please upload your profile picture.',
            'size' => 'File size should not be greater than 2 MB.',
            'mimes' => 'Supported only JPEG, JPG, PNG file type.'
        ],
    ],

    'category' => [
        'title' => [
            'required' => 'Please enter the category Title.',
            'unique' => 'Title is already taken.',
            'min' => 'Title can be :min - :max characters.',
            'max' => 'Title can be :min - :max characters.',
            'regex' => 'Only alphabets and in between space are allowed.',
            'string' => 'Only alphabets in between space are allowed.',
        ],
        'description' => [
            'required' => 'Please enter the description.',
            'string' => 'Only alphabets, numbers and in between space are allowed.',
        ],
    ],
    'cms' => [
        'name' => [
            'required' => 'Please enter the name.',
            'min' => 'Name can be :min - :max characters.',
            'max' => 'Name can be :min - :max characters.',
        ],
        'slug' => [
            'required' => 'Please enter the slug',
            'min' => 'Slug can be :min - :max characters.',
            'max' => 'Slug can be :min - :max characters.',
        ],
        'content' => [
            'required' => 'Please enter the content.',
            'min' => 'Content can be :min - :max characters.',
        ],
        'short_content' => [
            'min' => 'Short content can be :min - :max characters.',
            'max' => 'Short content can be :min - :max characters.',
        ],
        'page_title' => [
            'min' => 'Page title can be :min - :max characters.',
            'max' => 'Page title can be :min - :max characters.',
        ],
        'meta_title' => [
            'min' => 'Meta title can be :min - :max characters.',
            'max' => 'Meta title can be :min - :max characters.',
        ],
        'meta_description' => [
            'min' => 'Meta description can be :min - :max characters.',
        ],
        'status' => [
            'required' => 'Status field is required.',
        ],
        'image' => [
            'size' => 'Image size should not be greater than :min MB.',
            'mimes' => 'Supported only JPEG, JPG, PNG image type.'
        ],
    ],

    'user' => [
        'name' => [
            'required' => __('This field is required.'),
            'min' => __('Name must be between 2-50 characters.'),
            'max' => __('Name must be between 2-50 characters.'),
            'string' => 'Please enter only alphabetical values.',
            'regex' => __('Please enter only alphabetical values.'),

        ],
        'email' => [
            'required' => __('This field is required.'),
            'email' => __('Please enter valid email address.'),
            'regex' => __('Please enter valid email address.'),
            'unique' => 'This email is already in use. Please try signing in or using a different email address.',
        ],
        'phone_number' => [
            'required' => __('This field is required.'),
            'digits' => 'Please enter only numeric values.',
            'phoneUS' => 'Phone number must be of :digits digits.',
            'min' => __('Phone number must be 10 digits.'),
            'max' => __('Phone number must be 10 digits.'),
        ],
        'bio' => [
            'min' => 'Bio can be :min - :max characters.',
            'max' => 'Bio can be :min - :max characters.',
            'string' => 'Enter a valid String in Bio.',
        ],
        'username' => [
            'required' => __('This field is required.'),
            'unique' => 'This username is already in use. Please try signing in or using a different username.',
            'min' => __('Username must be at least 3 characters.'),
            'max' => __('Username must be between  3 - :max characters.'),
            'string' => 'Please enter only numeric or alphabetical values.',
            'regex' => __('Please enter only numeric or alphabetical values.'),
        ],
        'password' => [
            'required' => __('This field is required.'),
            'min' => __('Password must be between 8-32 characters.'),
            'max' => __('Password must be between 8-32 characters.'),
            'regex' => __("Please enter only numeric or alphabetical values or @#$%^&*. At least one uppercase, lowercase and numeric value required."),

        ],
        'confirm_password' => [
            'required' => __('This field is required.'),
            'equal' => __('Passwords must match.'),

        ],
        'profile_pic' => [
            'size' => 'File size should not be greater than :min MB.',
            'mimes' => 'Supported only JPEG, JPG, PNG file type.',
            'file' => 'filenot supported.',
        ],
        'cover_pic' => [
            'size' => 'File size should not be greater than :min MB.',
            'mimes' => 'Supported only JPEG, JPG, PNG file type.',
            'file' => 'filenot supported.',
        ],
        'country' => [
            'required' => 'Please select country.',
        ],
        'terms' => [
            'required' => 'Please check terms & condition.',
        ],
        'zipcode' => [
            'required' => __('This field is required.'),
            'regex' => __('Zip code is invalid.'),
        ],
        // 'complete_address' => [
        //     'required' => __('This field is required'),
        //     'min' => __('Complete address length should be 10.'),
        //     'max' => __('Complete address length should be 255.'),
        // ],
        'gov_id' => [
           'required' =>  __('This field is required'),
           'file' => 'The government ID must be a file of type: jpg, png, jpeg, pdf.',
           'max_size' => 'The government ID may not be greater than 2MB.',
        ],

    ],

    'media' => [
        'name' => [
            'required' => 'Please enter the name.',
            'string' => 'name must be a valid string.',
            'min' => 'Name can be :min - :max characters.',
            'max' => 'Name can be :min - :max characters.',
        ],
        'media_file' => [
            'required' => 'Please upload  media file.',
            'size' => 'File size should not be greater than :min MB.',
            'mimes' => 'Supported only JPEG, JPG, PNG ,WMV,AVI,MOV,3GP,MP4 file type.'
        ],
        'type' => [
            'required' => 'Please enter the rejection reason.',
            'string' => 'Only alphabets and in between space are allowed.',
        ],
    ],

    'cancelorder' => [
        'note' => [
            'required' => 'Please enter the cancellation note.',
            'min' => 'Cancellation note can be :min - :max characters.',
            'max' => 'Cancellation note can be :min - :max characters.',
        ],
    ],

    'productReview' => [
        'review' => [
            'required' => 'Please enter the review.',
            'min' => 'Review can be :min - :max characters.',
            'max' => 'Review can be :min - :max characters.',
        ],
        'rating' => [
            'required' => 'Please select rating.',
        ],
    ],

    'product' => [
        'name' => [
            'required' => 'Please enter Product Name.',
        ],
        'category' => [
            'required' => 'Please select Category.',
        ],
        'size' => [
            'required' => 'Please select Size.',
        ],
        'brand' => [
            'required' => 'Please select Brand.',
        ],
        'color' => [
            'required' => 'Please select Color.',
        ],
        'condition' => [
            'required' => 'Please select Condition.',
        ],
        'city' => [
            'required' => 'Please select City.',
        ],
        'neighborhood' => [
            'required' => 'Please select Neighborhood.',
        ],
        'description' => [
            'required' => 'Please enter Description.',
            'min' => 'Please enter Description (minimum 8 characters).',
            'max' => 'Description can be :min - :max characters.',
        ],
        'rent' => [
            'required' => 'Please enter Rental Price.',
            'min' => 'Rent can be :min - :max digit.',
            'max' => 'Rent can be :min - :max digit.',
            'regex' => 'Rent amount can be upto 2 digits from decimal.',
        ],
        'price' => [
            'required' => 'Please enter Retail Value.',
            'min' => 'Price can be :min - :max digit.',
            'max' => 'Price can be :min - :max digit.',
            'regex' => 'Price amount can be upto 2 digits from decimal.',
        ],
        'image' => [
            'required' => 'Please select the image.',
        ],
        'uploaded_image' => [
            'required' => 'Please upload at least :min images.',
        ],
        'location' => [
            'required' => 'Please enter the location.',
        ],
        'custom_location' => [
            'required' => 'Please enter the location as you know.',
        ],
        'product_market_value' => [
            'required' => 'Please enter product market value.',
        ],
        'product_link' => [
            'required' => 'Please enter Product link.',
        ],
        'state' => [
            'required' => 'Please enter State.',
        ],
        'city' => [
            'required' => 'Please enter City.',
        ],
        'product_name' => [
            'required' => 'Please enter Product name.',
        ],
        'subcategory' => [
            'required' => 'Please select Sub Category.',
        ],
        'pick_up_location' => [
            'required' => 'Please select Pick up location.',
        ],
        'min_rent_days' =>[
            'required' => 'Please enter the number of days required to rent an item.',
            'min' => 'Minimum rent days must be at least 1 days.',
            'max' => 'Maximum rent days must be 30 days.',
            'string' => 'Please enter only digit values.',
            'regex' => 'Please enter only digit values.',
            'range' => 'Please enter the days from 1 to 30.',
        ],
        'rent_price_day' =>[
            'required' => 'Please enter Rental price day.',
            'regex' => 'Please enter only digit values.',
        ],
        'rent_price_week' =>[
            'required' => 'Please enter Rental price week.',
            'regex' => 'Please enter only digit values.',
        ],
        'rent_price_month' =>[
            'required' => 'Please enter Rental price month.',
            'regex' => 'Please enter only digit values.',
        ],
        'non_available_dates' => [
            'required' =>  'This field is required',
        ],
    ],
];
