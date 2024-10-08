<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Order Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the category. You are free to 
    | change them to anything you want to customize your views to better 
    | match your application.
    |
    */

    'title'   =>  'Orders',
    'myActiveOrders'   =>  'My Active Orders',
    'product'   =>  'Product',
    'bookingDate'   =>  'Booking Date',
    'rentalAmount'   =>  'Rent',
    'securityAmount'   =>  'Security',
    'status'   =>  'Status',
    'action'   =>  'Action',
    'cancelOrder'   =>  'Cancel',
    'contactVendor'   =>  'Contact Vendor',
    'contactCustomer'   =>  'Contact Customer',
    'activeOrdersEmpty'   =>  'No active orders',
    'empty'   =>  'No orders',
    'orderId' => 'Order Id',
    'chatWithCustomer' => 'Chat with Customer',
    'chatWithVendor' => 'Chat with Vendor',
    'success' => 'Success',
    'error' => 'Error',
    'orderDetails' => 'Order Details',
    'message' => 'Your item is booked. Now discuss the details via chat',
    'goToMessage' => 'Go to Message',
    'bookingid' => 'Booking Id:',
    'productName' => 'Product Name:',
    'gmapAddress' => 'Location:',
    'customAddress' => 'Exact Location:',
    'pickUpDate' => 'Pick Up Date:',
    'pickUpTime' => 'Pick Up Time:',
    'returnDate' => 'Return Date:',
    'fields'    =>  [],
    'placeholders' => [],
    'validations'    =>  [
        'agreeRequired' => 'Please check the checkbox to agree with the terms and conditions',
        'imageRequired' => 'Please upload the image',
        'messageRequired' => 'Please enter the message',
        'attachmentExtenstion' => 'Please upload .jpeg, .jpg or .png file',
        'attachmentSize' => 'File size should not be more than 2MB',
    ],
    'messages' => [
        'success' => 'Message sent successfully.',
        'chatEmptyMessage' => 'Please send either message or attachment.',
        'minPickUpUploadImage' => 'Please upload atleast 2 images.',
        'minDisputeUploadImage' => 'Please upload atleast 2 images.',
        'cancel' => [
            'success' => 'Order cancelled successfully. The amount (if any) will be refunded with 5-7 business days to original payment method.',
            'error' => 'Order cancelled failed. Please try again.',
            'notAllowed' => 'Cancellation of this order is not allowed.',
            'paymentIncomplete' => 'Payment of order is not completed. Please complete the payment to cancel this order.'
        ]
    ]
];
