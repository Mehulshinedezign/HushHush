<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\PushToken;
use App\Models\Query;
use App\Models\RetailerPayout;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
                    'is_default' => '1'


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



    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = auth()->user();

            // Check if the user has any orders where they are either the buyer (user_id) or the retailer (retailer_id)
            $activeOrders = Order::where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('retailer_id', $user->id);
            })
                ->where(function ($query) {
                    $query->whereIn('status', ['Waiting', 'Picked up'])
                        ->orWhere('dispute_status', 'Yes');
                })
                ->exists();

            // If there are active orders, return an error message
            if ($activeOrders) {
                return response()->json([
                    'status' => false,
                    'data' => [
                        'message' => 'Your account cannot be deleted because you have active orders in "Waiting", "Picked up", or "Disputed" state. Please resolve them first.',
                    ],
                ], 403); // Return a 403 Forbidden status code
            }

            // If no active orders, proceed to delete products and the user account
            $products = Product::where('user_id', $user->id)->get();
            foreach ($products as $product) {
                $product->locations()->delete();
                foreach ($product->allImages as $image) {
                    Storage::disk('public')->delete($image->file_path);
                    $image->delete();
                }
                $product->delete();
            }

            // Delete user details
            UserDetail::where('user_id', $user->id)->delete();

            // Delete user's tokens (e.g., Laravel Passport or Sanctum)
            $user->tokens()->delete();

            // Update user's email to a unique value to prevent reuse
            $user->update(['email' => $user->email . '.' . now()->timestamp]);

            // Delete the user account
            $user->delete();

            DB::commit();
            return response()->json([
                'status' => true,
                'data' => [
                    'message' => 'Account deleted successfully.',
                ],
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'data' => [
                    'message' => 'Failed to delete account. Please try again.',
                    'error' => $e->getMessage(),
                ],
            ], 500);
        }
    }




    // public function test()
    // {
    //     // Check if the user is authenticated
    //     // if (!auth()->check()) {
    //     //     return response()->json(['status' => 'error', 'message' => 'User not authenticated'], 401);
    //     // }

    //     // $user = auth()->user();
    //     // // dd($user->pushToken->fcm_token);

    //     // if (!$user->pushToken || !$user->pushToken->fcm_token) {
    //     //     return response()->json(['status' => 'error', 'message' => 'FCM token not found'], 404);
    //     // }

    //     $payload = [
    //         'id' => 'dhfgdh',
    //         'content' => 'boldo  sir aaya notification',
    //         'role' =>'borrower',
    //         'type'=>'inquiery',
    //     ];
    //     $response = sendPushNotifications('f1P4fUqu_01Yjaw2zFCslf:APA91bH8Sn2-jeLPe6FkXnRlPKdvqMt_78qTTFUgsj1dmEsm3daDgTskJUjAljqDAY67ORV0I1Owe1WRipqRrHgi_9qD2_yaZEIM3e4L7Zz56DLH-okfs27opGvwFQxrH8V1J2Ox8-io', $payload);

    //     if ($response === false) {
    //         return response()->json(['status' => 'error', 'message' => 'Failed to send notification'], 500);
    //     }

    //     return response()->json(['status' => 'success', 'message' => 'Notification sent successfully', 'response' => $response]);
    // }


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
            // $validatedData = $request->validate([
            //     'query_receive' => 'required|in:0,1',
            //     'accept_item' => 'required|in:0,1',
            //     'reject_item' => 'required|in:0,1',
            //     'order_req' => 'required|in:0,1',
            //     'customer_order_pickup' => 'required|in:0,1',
            //     'lender_order_pickup' => 'required|in:0,1',
            //     'customer_order_return' => 'required|in:0,1',
            //     'lender_order_return' => 'required|in:0,1',
            //     'order_canceled_by_lender' => 'required|in:0,1',
            //     'order_canceled_by_customer' => 'required|in:0,1',
            // ]);

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
                // dd($order->retailePayout->amount);
                return [
                    'date' => $order->transaction->date ?? '',
                    'product_image_url' => $order->product->thumbnailImage->file_path ?? null,
                    'name' => $order->product->name ?? '',
                    'amount' => $order->retailePayout->amount ?? 'N/a',
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

    public function addFcm(Request $request)
    {
        $user = auth()->user();

        // Check if the user is authenticated
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated',
                'errors' => []
            ], 401);
        }

        // Validate the request input
        $validator = Validator::make($request->all(), [
            // 'device_id' => 'required|string',
            // 'device_type' => 'required|string',
            'fcm_token' => 'required|string',
        ]);

        // If validation fails, return the errors
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if the device ID already exists for the authenticated user
        $pushToken = PushToken::where('user_id', $user->id)
            ->first();

        // If the device ID exists, update the FCM token
        if ($pushToken) {
            $pushToken->update([
                // 'device_id' => $request->device_id,
                'fcm_token' => $request->fcm_token,
                // 'device_type' => $request->device_type
            ]);

            return response()->json([
                'status' => true,
                'message' => 'FCM token updated successfully',
                'data' => $pushToken
            ]);
        }

        // If the device ID does not exist, create a new record
        // $newPushToken = PushToken::create([
        //     'user_id' => $user->id,
        //     'device_id' => $request->device_id,
        //     'device_type' => $request->device_type,
        //     'fcm_token' => $request->fcm_token
        // ]);

        // return response()->json([
        //     'status' => true,
        //     'message' => 'FCM token added successfully',
        //     'data' => $newPushToken
        // ]);
    }


    public function test()
    {
        // Assuming user authentication is handled elsewhere

        $payload = [
            'id' => 'dhfgdh',
            'content' => 'boldo sir aaya notification',
            'role' => 'borrower',
            'type' => 'inquiery',
        ];

        // Example FCM token
        $fcmToken = 'f1P4fUqu_01Yjaw2zFCslf:APA91bH8Sn2-jeLPe6FkXnRlPKdvqMt_78qTTFUgsj1dmEsm3daDgTskJUjAljqDAY67ORV0I1Owe1WRipqRrHgi_9qD2_yaZEIM3e4L7Zz56DLH-okfs27opGvwFQxrH8V1J2Ox8-io';

        $response = sendPushNotifications($fcmToken, $payload);

        if ($response === false) {
            return response()->json(['status' => 'error', 'message' => 'Failed to send notification'], 500);
        }

        return response()->json(['status' => 'success', 'message' => 'Notification sent successfully', 'response' => $response]);
    }

    public function getAddress()
    {
        try {
            $address = UserDetail::where('user_id', auth()->id())->get();
            // dd($address);
            return response()->json([
                'status' => true,
                'data' => $address,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => []
            ], 500);
        }
    }

    public function saveAddress(Request $request)
    {
        // dd('here', $request->all());
        Log::info($request->all());
        $user = auth()->user();

        try {
            // Validate input data
            $validator = Validator::make($request->all(), [
                'zipcode' => 'required',
                'address1' => 'required',
                // 'address2' => 'required',
                'country' => 'required',
                'state' => 'required',
                'city' => 'required',
            ]);

            // Validation fails
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation Error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Create or update the address
            $address = UserDetail::updateOrCreate(
                ['id' => $request->address_id],
                [
                    'user_id' => auth()->id(),
                    'address1' => $request->address1,
                    'address2' => $request->address2 ?? null,
                    'city' => $request->city,
                    'state' => $request->state,
                    'country' => $request->country,
                    'zipcode' => $request->zipcode ?? 'N/A',
                    'complete_address' => $request->complete_address,
                    'is_default' => $request->has('is_default') ? $request->is_default : '0', // Ensure a default value is set if not provided
                ]
            );

            // Handle the default address logic
            if ($request->is_default == '1') {
                // Reset other default addresses for the user
                UserDetail::where('user_id', auth()->id())
                    ->where('is_default', '1')
                    ->update(['is_default' => '0']);

                UserDetail::where('user_id', $address->user_id)->update(['is_default' => '0']);

                // Then, set the selected address as the default
                $is_default = UserDetail::where('id', $address->id)->update(['is_default' => '1']);

                // Set the newly created/updated address as default

            }

            return response()->json([
                'status' => true,
                'data' => $address,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => []
            ], 500);
        }
    }


    public function addressDestroy($id)
    {
        $user = auth()->user();

        $multipleAddresses = UserDetail::where('user_id', $user->id)->get();

        if ($multipleAddresses->count() == 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'You cannot delete the only address associated with your account.',
            ], 400);
        }

        $address = UserDetail::findOrFail($id);

        if ($address->is_default == '1') {
            $newDefaultAddress = UserDetail::where('user_id', $user->id)
                ->where('id', '!=', $id)
                ->first();

            if ($newDefaultAddress) {
                $newDefaultAddress->update(['is_default' => '1']);
            }
        }

        $address->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Address deleted successfully, and a new default address has been assigned if necessary.',
        ], 200);
    }
}
