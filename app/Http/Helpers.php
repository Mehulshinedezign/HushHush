<?php

use App\Models\PhoneOtp;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use App\Models\{User, Category, AdminSetting, Brand, City, Color, Country, EmailOtp, NeighborhoodCity, Order, Size, OrderItem, State};
use Carbon\Carbon;
use FontLib\TrueType\Collection;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

if (!function_exists('encrypt_userdata')) {
    function encrypt_userdata(string $data)
    {
        try {
            $encryptData = Crypt::encryptString($data);
            return $encryptData;
        } catch (\Exception $e) {
            abort('403');
        }
    }
}
if (!function_exists('decrypt_userdata')) {
    function decrypt_userdata(string $data)
    {
        try {
            $decryptData = Crypt::decryptString($data);
            return $decryptData;
        } catch (\Exception $e) {
            abort('403');
        }
    }
}

if (!function_exists('jsencode_userdata')) {
    function jsencode_userdata($data, string $encryptionMethod = null, string $secret = null)
    {
        if (empty($data)) {
            return "";
        }
        $encryptionMethod = config('app.encryptionMethod');
        $secret = config('app.secrect');
        try {
            $iv = substr($secret, 0, 16);
            $jsencodeUserdata = str_replace('/', '!', openssl_encrypt($data, $encryptionMethod, $secret, 0, $iv));
            $jsencodeUserdata = str_replace('+', '~', $jsencodeUserdata);
            return $jsencodeUserdata;
        } catch (\Exception $e) {
            return null;
        }
    }
}
if (!function_exists('jsdecode_userdata')) {
    function jsdecode_userdata($data, string $encryptionMethod = null, string $secret = null)
    {
        if (empty($data))
            return null;
        $encryptionMethod = config('app.encryptionMethod');
        $secret = config('app.secrect');
        try {
            $iv = substr($secret, 0, 16);
            $data = str_replace('!', '/', $data);
            $data = str_replace('~', '+', $data);
            $jsencodeUserdata = openssl_decrypt($data, $encryptionMethod, $secret, 0, $iv);
            return $jsencodeUserdata;
        } catch (\Exception $e) {
            return null;
        }
    }
}

// get parent category
if (!function_exists('getParentCategory')) {
    function getParentCategory()
    {
        return Category::where('status', '1')->where('parent_id', Null)->get();
    }
}

// get parent category
if (!function_exists('getChild')) {
    function getChild($id)
    {
        // dd('here' ,$id);
        return Category::where('status', '1')->where('parent_id', $id)->get();
    }
}

// Store image
if (!function_exists('store_image')) {
    function store_image($data, string $path)
    {
        if (empty($data)) {
            return null;
        }
        try {
            $file = $data->getClientOriginalName();
            $url = Storage::put($path, $data);
            return ['name' => $file, 'url' => $url];
        } catch (\Exception $e) {
            return null;
        }
    }
}

// Store image by S3 bucket
if (!function_exists('s3_store_image')) {
    function s3_store_image($data, string $path)
    {
        if (empty($data)) {
            return null;
        }
        try {
            $img_path = $data->store($path, 'public');
            $url = Storage::disk('public')->url($img_path);
            $fileName = basename($img_path);
            return ['name' => $fileName, 'url' => $url];
        } catch (\Exception $e) {
            return null;
        }
    }
}

if (!function_exists('getTransactionAmount')) {
    function getTransactionAmount($rent, $rentalDays)
    {
        // get the item price
        $transactionSetting = AdminSetting::where('key', 'renter_transaction_fee')->first();
        $transactionAmount = $transactionSetting->value;
        if ($transactionSetting->type == 'Percentage') {
            $transactionAmount = (($rent * $rentalDays) * ($transactionSetting->value)) / 100;
        }
        $transactionAmount = number_format((float) $transactionAmount, 2, '.', '');

        return $transactionAmount;
    }
}

// get brands
if (!function_exists('getBrands')) {
    function getBrands()
    {
        return Brand::where('status', '1')->orderBy('name', 'ASC')->get();
    }
}

// get colors
if (!function_exists('getColors')) {
    function getColors()
    {
        return Color::where('status', '1')->orderBy('name', 'asc')->get();
    }
}
if (!function_exists('getColorsName')) {
    function getColorsName($id)
    {
        $colorName = Color::where('id', $id)->first();
        return $colorName->name;
    }
}
// get type
if (!function_exists('getTypes')) {
    function getTypes()
    {
        return ['type1' => 'Type 1 (XS/P, S, M, L, XL)', 'type2' => 'Type 2 (0, 2, 4, 6, 8, etc.)', 'type3' => 'Type 3 (23, 24, 25, 26, 27, etc.)', 'type4' => 'Type 4 (OS)', 'type5' => 'Type 5 (5, 5.5, 6, 6.5, 7, etc.)', 'type6' => 'Type 6 (35, 35.5, 36, 36.5, 37, etc.)', 'other' => 'Other'];
    }
}

// get all size
if (!function_exists('getAllsizes')) {
    function getAllsizes()
    {
        return Size::where('status', '1')->orderBy('id', 'asc')->get();
    }
}

// check chats exists
if (!function_exists('check_chat_exist')) {
    function check_chat_exist($receiver_id, $order_id)
    {
        return DB::table('chats')->where(function ($q) use ($receiver_id, $order_id) {
            $q->where([
                ['user_id', '=', auth()->user()->id],
                ['retailer_id', '=', $receiver_id],
                ['order_id', '=', $order_id],
            ])->orwhere([
                ['retailer_id', '=', auth()->user()->id],
                ['user_id', '=', $receiver_id],
                ['order_id', '=', $order_id],
            ]);
        })->first();
    }
}
// testing chat
if (!function_exists('check_chat_exist_or_not')) {
    function check_chat_exist_or_not($receiver_id)
    {
        return DB::table('chats')->where(function ($q) use ($receiver_id) {
            $q->where([
                ['user_id', '=', auth()->user()->id],
                ['retailer_id', '=', $receiver_id],
                // ['order_id', '=', $order_id],
            ])->orwhere([
                ['retailer_id', '=', auth()->user()->id],
                ['user_id', '=', $receiver_id],
                // ['order_id', '=', $order_id],
            ]);
        })->first();
    }
}
//
if (!function_exists('leaveReview')) {
    function leaveReview($id)
    {
        $orders = Order::select('id', 'user_id', 'status', 'from_date')->with(['item' => function ($q) use ($id) {
            $q->whereProductId($id)->whereStatus('Completed');
        }])->whereUserId(auth()->user()->id)->whereStatus('Completed')->get();
        // dd($orders->toArray());
        $orderData = [];
        foreach ($orders as $order) {
            if ($order->item) {
                $orderData['order_id'] = $order->item->order_id;
            }
        }
        return $orderData;
    }
}

if (!function_exists('get_size_by_type')) {
    function get_size_by_type($type)
    {
        return Size::whereType($type)->orderBy('id', 'asc')->get();
    }
}

if (!function_exists('check_order_list_paginate')) {
    function check_order_list_paginate($status)
    {
        return Order::OrderByStatus($status)->where('user_id', auth()->user()->id)->count();
    }
}

if (!function_exists('check_order_list_paginate_retailer')) {
    function check_order_list_paginate_retailer($status)
    {
        return OrderItem::OrderByStatus($status)->where('retailer_id', auth()->user()->id)->count();
    }

    if (!function_exists('cityneighborhood')) {
        function cityneighborhood()
        {
            return NeighborhoodCity::where('parent_id', null)->get();
        }
    }

    if (!function_exists('neighborhoodcity')) {
        function neighborhoodcity()
        {
            return NeighborhoodCity::where('parent_id', null)->get();
        }
    }
    if (!function_exists('headerneighborhoodcity')) {
        function headerneighborhoodcity()
        {
            return NeighborhoodCity::where('parent_id', null)->orderBy('name', 'ASC')->get();
        }
    }

    if (!function_exists('headerneighborhood')) {
        function headerneighborhood($id = null)
        {
            // if($request()->)
            return NeighborhoodCity::where('parent_id', $id)->orderBy('name', 'ASC')->get();
        }
    }
    if (!function_exists('adminsetting')) {
        function adminsetting()
        {
            return AdminSetting::where('key', 'order_commission')->first();
        }
    }

    if (!function_exists('states')) {
        function states()
        {
            return State::orderBy('name', 'ASC')->get();
        }
    }

    if (!function_exists('cities')) {
        function cities($state_id)
        {
            return City::where('state_id', $state_id)->orderBy('name', 'ASC')->get();
        }
    }

    if (!function_exists('country')) {
        function country()
        {
            return Country::orderBy('name', 'ASC')->get();
        }
    }

    if (!function_exists('phoneNumberValidate')) {
        function phoneNumberValidate($user)
        {
            $status = PhoneOtp::where('user_id', $user)->first();

            // Check if the record exists before accessing the status property
            if (!$status) {
                return true; // Assuming no record means not verified
            }

            return $status->status == 1 ? false : true;
        }
    }

    if (!function_exists('emailValidate')) {
        function emailValidate($user)
        {
            $status = EmailOtp::where('user_id', $user)->first();

            // Check if the record exists before accessing the status property
            if (!$status) {
                return true; // Assuming no record means not verified
            }

            return $status->status == 1 ? false : true;
        }
    }

    if (!function_exists('diffBetweenToDate')) {
        function diffBetweenToDate($fromDate, $toDate)
        {
            $date = Carbon::parse($fromDate);
            $now = Carbon::parse($toDate);
            if ($date == $now) {
                return 1;
            }
            $diff = $date->diffInDays($now);

            return $diff;
        }
    }

    if (!function_exists('getsizes')) {
        function getsizes($id)
        {
            $sizeName =  Size::where('id', $id)->first();
            return $sizeName->name;
        }
    }
    if (!function_exists('getColorsName')) {
        function getColorsName($id)
        {
            $colorName = Color::where('id', $id)->first();
            return $colorName->name;
        }
    }
    if (!function_exists('getBrandsName')) {
        function getBrandsName($id)
        {
            $brandName = Brand::where('id', $id)->first();
            return $brandName->name;
        }
    }





    if (!function_exists('sendPushNotifications')) {
        function sendPushNotifications($token, $payload)
        {
            if (!is_null($token)) {
                $url = 'https://fcm.googleapis.com/fcm/send';
                $api_key = 'AIzaSyDovLKo3djdRbs963vqKdbj-geRWyzMTrg'; // Replace with your FCM Server Key

                $notification = [
                    'title' => 'Notification Title', // You can customize this
                    'body' => $payload['content'],
                    'order_id' => $payload['id'],
                ];

                $fcmNotification = [
                    'to' => $token,
                    'notification' => $notification,
                    'priority' => 'high',
                    'data' => $payload
                ];

                $headers = [
                    'Authorization: key=' . $api_key,
                    'Content-Type: application/json'
                ];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
                $result = curl_exec($ch);
                curl_close($ch);

                return $result;
            }

            return false;
        }
    }

}
