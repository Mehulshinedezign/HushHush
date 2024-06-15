<?php

return [
    'fields'    =>  [
        'name' => 'Name',
        'email' => 'Email',
        'address1' => 'Address line 1',
        'address2' => 'Address line 2',
        'country' => 'Country',
        'state' => 'State',
        'city' => 'City',
        'postalCode' => 'Postal Code',
    ],
    'placeholders' => [
        'name' => 'Name',
        'email' => 'Email',
        'address1' => 'Address line 1',
        'address2' => 'Address line 2',
        'country' => 'Country',
        'state' => 'State',
        'city' => 'City',
        'postalCode' => 'Postal Code',
    ],
    'validations'    =>  [
        'nameRequired' => 'Please enter the name',
        'emailRequired' => 'Please enter the email',
        'emailType' => 'Please enter the valid email',
        'address1Required' => 'Please enter the address',
        'countryRequired' => 'Please select the country',
        'stateRequired' => 'Please select the state',
        'cityRequired' => 'Please select the city',
        'phoneRequired' => 'Please enter the phone number',
        'postalCodeRequired' => 'Please enter the postal code',
        'productRequired' => 'Please select the product',
        'productExist' => 'Product does not exist',
        'customerLocationRequired' => 'Please select the product',
        'latitudeRequired' => 'Something wrong, try again',
        'longitudeRequired' => 'Something wrong, try again',
        'oops' => 'Something Wrong',
        'productLocationRequired' => 'Product location is missing',
        'productLocationExist' => 'Product location does not exist',
        'reservationRequired' => 'Reservation date is missing',
        'reservationDatesAfterOrEqual' => 'The reservation dates must be a date after or equal to ' . date('Y-m-d'),
    ],
    'messages' => [
        'notAvailable' => 'Product reservation not available for the selected date',        
    ]
];