<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Query;
use App\Models\RetailerPayout;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated',
                    'errors' => []
                ], 401);
            }

            $userData = User::find($user->id, ['id', 'name', 'email', 'phone_number', 'profile_file', 'profile_url', 'zipcode', 'email_notifications', 'push_notifications', 'country_code', 'otp_is_verified', 'email_verified_at']);
            $userDetails = UserDetail::where('user_id', $user->id)->first();

            $response = [
                'user' => $userData,
                'address' => $userDetails,
            ];

            return response()->json([
                'status' => true,
                'message' => 'User details retrieved successfully',
                'data' => $response
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => []
            ], 500);
        }
    }

    public function edit()
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated',
                    'errors' => []
                ], 401);
            }

            $userDetails = UserDetail::where('user_id', $user->id)->first();

            $response = [
                'user' => $user,
                'address' => $userDetails,
            ];

            return response()->json([
                'status' => true,
                'message' => 'User details retrieved successfully',
                'data' => $response
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => []
            ], 500);
        }
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated',
                    'errors' => []
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'string|max:255',
                'email' => 'string|email|max:255|unique:users,email,' . $user->id,
                'phone_number' => 'string|max:20' . $user->id,
                'profile_pic' => 'nullable|file',
                'zipcode' => 'nullable|string|max:20',
                'email_notifications' => 'boolean',
                'push_notifications' => 'boolean',
                'address1' => 'nullable|string|max:255',
                'address2' => 'nullable|string|max:255',
                'country_id' => 'nullable|integer',
                'state_id' => 'nullable|integer',
                'city_id' => 'nullable|integer',
                'about' => 'nullable|string|max:1000',
                'country_code' => 'nullable|string|max:20',

            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
            }

            // if ($request->email || $request->phone_number) {
            //     return response()->json([
            //         'status' => false,
            //         'message' => "Email or Phone number can't be updated",
            //         'errors' => []
            //     ], 422);
            // }

            if ($request->hasFile('profile_pic')) {
                $profilePicPath = $request->file('profile_pic')->store('profile_pictures', 'public');
                $user['profile_url'] = $profilePicPath;
                $user['profile_file'] = $profilePicPath;
            }
            $user->update($request->all());

            // User details entry
            $completeadrres = $request->complete_address ?? $user->userDetail->complete_address ?? Null;
            $address1 = $request->address1 ?? $user->userDetail->address1 ?? Null;
            $address2 = $request->address2 ?? $user->userDetail->address2 ?? Null;
            $about = ($request->about == '') ? NUll : $request->about;
            $country = $request->country ?? $user->userDetail->country ?? Null;
            $state = $request->state ?? $user->userDetail->state ?? Null;
            $city = $request->city ?? $user->userDetail->city ?? Null;
            $zipcode = $request->zipcode ?? $user->userDetail->zipcode ?? Null;
            // $about = $request->about_me ?? $user->userDetail->about?? null;
            $userDetails = UserDetail::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'complete_address' => $completeadrres,
                    'address1' => $address1,
                    'address2' => $address2,
                    'country' => $country,
                    'state' => $state,
                    'city' => $city,
                    'about' => $about,
                    'zipcode' => $zipcode,


                ]
            );

            // dd($request->all(), $user);

            $response = [
                'user' => $user,
                'address' => $userDetails,
            ];
            DB::commit();
            // dd($user,$userDetails);
            return response()->json([
                'status' => true,
                'message' => 'User updated successfully',
                'data' => $response
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => []
            ], 500);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated',
                    'errors' => []
                ], 401);
            }
            $validator = Validator::make($request->all(), [
                'old_password' => 'required',
                'new_password' => 'required|string|min:8|max:32',
            ]);
            // dd($request->all(),$validator);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
            }

            if (!Hash::check($request->old_password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'errors' => ['old_password' => ['Old password is incorrect']],
                ], 422);
            }

            if (Hash::check($request->new_password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'errors' => ['new_password' => ['New password must be different from the current password']],
                ], 422);
            }

            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Password changed successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => []
            ], 500);
        }
    }

    public function Stats(Request $request)
    {
        try {
            $user = auth()->user();

            $productCount = Product::countUserProducts($user->id);
            $queryCount = Query::countUserQueries($user->id);
            $completedOrdersCount = Order::countCompletedOrders($user->id);
            $otherOrdersCount = Order::countOtherOrders($user->id);
            $earning = RetailerPayout::calculateTotalEarnings($user->id);

            return response()->json([
                'status' => true,
                'data' => [
                    [
                        'name' => 'Total items added for rent',
                        'count' => $productCount,
                        'groupname' => 'Ionicons',
                        'iconname' => 'newspaper-outline',
                    ],
                    [
                        'name' => 'Total booking requests received',
                        'count' => $queryCount,
                        'groupname' => 'Ionicons',
                        'iconname' => 'newspaper-outline',
                    ],
                    [
                        'name' => 'Total completed bookings',
                        'count' => $completedOrdersCount,
                        'groupname' => 'Ionicons',
                        'iconname' => 'newspaper-outline',
                    ],
                    [
                        'name' => 'Total active bookings',
                        'count' => $otherOrdersCount,
                        'groupname' => 'Ionicons',
                        'iconname' => 'newspaper-outline',
                    ],
                    [
                        'name' => 'Total revenue generated',
                        'count' => $earning,
                        'groupname' => 'Ionicons',
                        'iconname' => 'newspaper-outline',
                    ],
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => []
            ], 500);
        }
    }



    public function destory(Request $request)
    {
        DB::beginTransaction();
        $user = auth()->user();
        $products = Product::where('user_id', $user->id)->get();
        foreach ($products as $product) {
            $product->locations()->delete();

            foreach ($product->allImages as $image) {
                Storage::disk('public')->delete($image->file_path);
                $image->delete();
            }

            $product->delete();
        }

        UserDetail::where('user_id', $user->id)->delete();

        $user->delete();

        // $user->logout();
        $user->tokens()->delete();

        DB::commit();
        return response()->json([
            'status' => true,
            'data' => [
                'message' => 'Account deleted',

            ],
        ], 200);
    }


    public function test()
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return response()->json(['status' => 'error', 'message' => 'User not authenticated'], 401);
        }

        $user = auth()->user();
        dd($user->pushToken->fcm_token);

        if (!$user->pushToken || !$user->pushToken->fcm_token) {
            return response()->json(['status' => 'error', 'message' => 'FCM token not found'], 404);
        }

        $payload = [
            'id' => 'dhfgdh',
            'content' => 'You have received an inquiry about a product'
        ];
        $response = sendPushNotifications($user->pushToken->fcm_token, $payload);

        if ($response === false) {
            return response()->json(['status' => 'error', 'message' => 'Failed to send notification'], 500);
        }

        return response()->json(['status' => 'success', 'message' => 'Notification sent successfully', 'response' => $response]);
    }


    public function userNotification(Request $request)
    {
        try {
            $user = auth()->user();
            $user_notification = $user->usernotification;

            return response()->json([
                'status' => true,
                'data' => [
                    'user_id' => $user_notification->user_id,
                    'query_receive' => $user_notification->query_receive,
                    'accept_item' => $user_notification->accept_item,
                    'reject_item' => $user_notification->reject_item,
                    'order_req' => $user_notification->order_req,
                    'customer_order_pickup' => $user_notification->customer_order_pickup,
                    'lender_order_pickup' => $user_notification->lender_order_pickup,
                    'customer_order_return' => $user_notification->customer_order_return,
                    'lender_order_return' => $user_notification->lender_order_return,
                    'order_canceled_by_lender' => $user_notification->order_canceled_by_lender,
                    'order_canceled_by_customer' => $user_notification->order_canceled_by_customer,

                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => []
            ], 500);
        }
    }


    public function updateNotification(Request $request)
    {
        try {
            // Validate the incoming request
            $validatedData = $request->validate([
                'query_receive' => 'required|in:0,1',
                'accept_item' => 'required|in:0,1',
                'reject_item' => 'required|in:0,1',
                'order_req' => 'required|in:0,1',
                'customer_order_pickup' => 'required|in:0,1',
                'lender_order_pickup' => 'required|in:0,1',
                'customer_order_return' => 'required|in:0,1',
                'lender_order_return' => 'required|in:0,1',
                'order_canceled_by_lender' => 'required|in:0,1',
                'order_canceled_by_customer' => 'required|in:0,1',
            ]);

            $user = auth()->user();

            // Make sure user is authenticated
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated',
                    'errors' => []
                ], 401);
            }
            if (!$user->usernotification) {
                $userNotification = $user->usernotification->create([
                    'query_receive' => $request->query_receive,
                    'accept_item' => $request->accept_item,
                    'reject_item' => $request->reject_item,
                    'order_req' => $request->order_req,
                    'customer_order_pickup' => $request->customer_order_pickup,
                    'lender_order_pickup' => $request->lender_order_pickup,
                    'customer_order_return' => $request->customer_order_return,
                    'lender_order_return' => $request->lender_order_return,
                    'order_canceled_by_lender' => $request->order_canceled_by_lender,
                    'order_canceled_by_customer' => $request->order_canceled_by_customer,
                ]);
            } else {
                $userNotification = $user->usernotification->update([
                    'query_receive' => $request->query_receive,
                    'accept_item' => $request->accept_item,
                    'reject_item' => $request->reject_item,
                    'order_req' => $request->order_req,
                    'customer_order_pickup' => $request->customer_order_pickup,
                    'lender_order_pickup' => $request->lender_order_pickup,
                    'customer_order_return' => $request->customer_order_return,
                    'lender_order_return' => $request->lender_order_return,
                    'order_canceled_by_lender' => $request->order_canceled_by_lender,
                    'order_canceled_by_customer' => $request->order_canceled_by_customer,
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Notification settings updated successfully',
                'data' => $user->usernotification
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => []
            ], 500);
        }
    }

    public function earnings(Request $request)
    {
        try {
            $user = auth()->user();
            $orders = Order::with('product.thumbnailImage', 'retailer.vendorPayout', 'transaction')
                ->where('retailer_id', $user->id)
                ->get();
            $data = $orders->map(function ($order) {
                return [
                    'date' => $order->transaction->date ?? '',
                    'product_image_url' => $order->product->thumbnailImage->file_path ?? null,
                    'name' => $order->product->name ?? '',
                    'amount' => $order->retailer->vendorPayout[0]->amount ?? 'N/a',
                ];
            });

            return response()->json([
                'status' => true,
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => []
            ], 500);
        }
    }
}
