<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Country, State, City, Notification, NotificationSetting, Product, RetailerBankInformation, UserDocuments, User, UserCard};
use Hash, Stripe, Exception, DateTime;
use App\Http\Requests\{ProfileRequest, UserDetailRequest};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\{Auth, Session};

class ProfileController extends Controller
{
    public function handle(){
        return view('edit_prodcut');
    }
    public function profile()
    {
        // dd("HERE PPPPPPPPPPPPPPPP");
        $user = auth()->user();
        // $selectedCountryId = $user->country_id;
        // if (is_null($user->country_id)) {
        //     $selectedCountryId = Country::where('iso_code', 'US')->pluck('id')->first();
        // }

        // $countries = Country::all();
        // $states = State::where('country_id', $selectedCountryId)->get();
        // $cities = City::where('state_id', $user->state_id)->get();
        // $notAvailable = 'N/A';
        // if (auth()->user()->role->name == 'customer') {
        //     $file = 'customer.profile';
        // } elseif (auth()->user()->role->name == 'admin') {
        //     $file = 'admin.profile';
        // } else {
        //     $file = 'retailer.profile';
        // }

        $products = Product::where('user_id',$user->id)->get();
        return view('customer.profile',compact('products'));
    }

    public function edit_profile()
    {
        $user = User::with('userDetail')->where('id', auth()->user()->id)->first();
        if (isset($user->userDetail->country_id)) {
            $selectedCountryId = $user->userDetail->country_id;
        } else {

            $selectedCountryId = Country::where('iso_code', 'US')->pluck('id')->first();
        }
        // dd($selectedCountryId);


        $countries = Country::where('iso_code', 'US')->get();
        $states = State::where('country_id', $selectedCountryId)->get();
        $cities = City::where('state_id', $user->state_id)->get();
        $notAvailable = 'N/A';

        // card index
        $cards = UserCard::where('user_id', auth()->user()->id)->get();
        // dd($user->getCashier());
        $stripePublicKey = config('cashier.key');
        $stripeCustomer = $user->createOrGetStripeCustomer();
        $intent = $user->createSetupIntent();

        // bankdetails
        $bankDetail = RetailerBankInformation::where('retailer_id', $user->id)->first();
        return view('customer.edit_profile', compact('user', 'countries', 'states', 'cities', 'selectedCountryId', 'notAvailable', 'cards', 'stripePublicKey', 'stripeCustomer', 'intent', 'bankDetail'));
    }

    public function saveUserDetail(UserDetailRequest $request)
    {
        try {
            $user = auth()->user();
            $data = [
                'username' => $request->username,
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'zipcode' => $request->postcode,
            ];

            $userdetail = [
                'address1' => $request->address1,
                'address2' => $request->address2,
                'country_id' => $request->country,
                'state_id' => $request->state,
                'city_id' => $request->city,
                'about' => $request->about
            ];

            if (auth()->user()->role->name == 'admin') {
                $data['email'] = $request->email;
            }

            if ($request->hasFile('profile_pic')) {
                $profile = s3_store_image($request->file('profile_pic'), 'profiles');
                // dd($profile);
                $data['profile_file'] = $profile['name'];
                $data['profile_url'] = $profile['url'];
                if (!is_null($user->profile_url)) {
                    Storage::disk('public')->delete('profiles/' . $user->profile_file);
                }
            }

            $user->update($data);

            if ($user->userDetail) {
                $user->userDetail()->update($userdetail);
            } else {
                $user->userDetail()->create($userdetail);
            }

            $userDocument = UserDocuments::where('user_id', $user->id)->first();

            if ($request->hasFile('proof') && auth()->user()->is_approved == 0) {

                $proof = s3_store_image($request->file('proof'), 'admin/proof');
                if ($proof != null) {
                    $uploadedFileName = $request->file('proof')->getClientOriginalName();
                    auth()->user()->documents()->create([
                        'file' => $proof['name'],
                        'url' => $proof['url'],
                        'uploaded_file_name' => $uploadedFileName,
                    ]);
                }

                if (isset($userDocument) && !is_null($userDocument->file)) {
                    Storage::disk('s3')->delete('admin/proof/' . $userDocument->file);
                    // Storage::delete($userDocument->file);
                }
                // $uploadedFileName = $request->file('proof')->getClientOriginalName();
                // $docPath = $request->file('proof')->store('documents', 's3');
                // $docUrl = Storage::disk('s3')->url($docPath);
                // $docName = basename($docPath);
                // auth()->user()->documents()->create([
                //     'file' => $docName,
                //     'url' => $docUrl,
                //     'uploaded_file_name' => $uploadedFileName,
                // ]);

                // if (!is_null($userDocument)) {
                //     // remove from s3
                //     Storage::disk('s3')->delete('documents/'. $userDocument->file);
                //     $userDocument->delete();
                // }
            }

            // if (isset($userDocument) && $user->role_id != $request->user_role){
            //     $user->update(['role_id' => $request->user_role]);
            //     if($request->user_role == '2'){
            //         return redirect()->route("retailer.dashboard");
            //     }else{
            //         return redirect()->route("index");
            //     }
            // }

            return redirect()->route('edit-account', ['tab' => 'nav-home'])->with('message', __('user.messages.profileUpdated'));
        } catch (Exception $exception) {
            return redirect()->back();
        }
    }

    public function switch_profile($role)
    {
        $user = auth()->user();
        $user->update(['role_id' => $role]);
        if ($role == '2') {
            return redirect()->route("retailer.dashboard")->with(['msg' => 'Profile switch in Lender']);
        } else {
            return redirect()->route("index")->with(['msg' => 'Profile switch in Borrower']);
        }
    }

    public function updatePassword(ProfileRequest $request)
    {
        $currentPassword = $request->current_password;
        $user = auth()->user();

        if (!Hash::check($currentPassword, $user->password)) {
            return back()->with('error', __('user.validations.currentPasswordIncorrect'));
        }

        $newPassword = Hash::make($request->new_password);
        User::where('id', $user->id)->update(['password' => $newPassword]);

        if (Hash::check($request->new_password, $user->password)) {
            return redirect()->back()->with('error', __('user.validations.oldNewPassword'));
        }

        // return redirect()->back()->with('error', __('user.validations.oldNewPassword'));

        // return redirect()->route('edit-account', ["tab" => "nav-profile"])->with('success', __('user.messages.passwordChanged'));
        return redirect()->route('index')->with('success', __('user.messages.passwordChanged'));
    }


    public function notificationSetting(Request $request)
    {
        if ($request->ajax() && $request->isMethod('post')) {
            $user = auth()->user();
            $notification = NotificationSetting::where('user_id', $user->id)->first();
            // dd("wkjcbkjcjkcbjk", $request->toArray(), $notification);
            if (is_null($notification) && $request->has('key') && $request->has('value')) {
                NotificationSetting::create([
                    'user_id' => $user->id,
                    'order_placed' => ($request->key == 'order_placed') ? $request->value : 'off',
                    'order_pickup' => ($request->key == 'order_pickup') ? $request->value : 'off',
                    'order_return' => ($request->key == 'order_return') ? $request->value : 'off',
                    'order_cancelled' => ($request->key == 'order_cancelled') ? $request->value : 'off',
                    'payment' => ($request->key == 'payment') ? $request->value : 'off',
                    'welcome_mail' => ($request->key == 'welcome_mail') ? $request->value : 'off',
                    'feedback' => ($request->key == 'feedback') ? $request->value : 'off',
                    'user_booking_request' => ($request->key == 'user_booking_request') ? $request->value : 'off',
                    'lender_accept_booking_request' => ($request->key == 'lender_accept_booking_request') ? $request->value : 'off',
                    'reminder_for_pickup_time_location' => ($request->key == 'reminder_for_pickup_time_location') ? $request->value : 'off',
                    'reminder_for_drop_off_time_location' => ($request->key == 'reminder_for_drop_off_time_location') ? $request->value : 'off',
                    'rate_your_experience' => ($request->key == 'rate_your_experience') ? $request->value : 'off',
                    'item_we_think_you_might_like' => ($request->key == 'item_we_think_you_might_like') ? $request->value : 'off',
                    'lender_receives_booking_request' => ($request->key == 'lender_receives_booking_request') ? $request->value : 'off',
                    'lender_send_renter_first_msg' => ($request->key == 'lender_send_renter_first_msg') ? $request->value : 'off',
                    'renter_send_lender_first_msg' => ($request->key == 'renter_send_lender_first_msg') ? $request->value : 'off',
                    'reminder_to_start_listing_items' => ($request->key == 'reminder_to_start_listing_items') ? $request->value : 'off',
                ]);
            } elseif (!is_null($notification)) {
                $notification->update([$request->key => $request->value]);
            }
            if ($request->value == 'off') {
                return response()->json(['status' => 200, 'title' => 'Success', 'message' => 'Email preference has been deactivated successfully.']);
            } else {
                return response()->json(['status' => 200, 'title' => 'Success', 'message' => 'Email preference has been activated successfully']);
            }
        }

        return redirect()->back();
    }

    public function notifications(Request $request)
    {
        $user = auth()->user();
        Notification::where('receiver_id', $user->id)->update(['is_read' => 1]);
        if ($user->role->name == 'customer') {
            $file = 'customer.notification_list';
            $route = 'notifications';
        } elseif ($user->role->name == 'admin') {
            $file = 'admin.notification_list';
            $route = 'admin.notifications';
        } else {
            $file = 'retailer.notification_list';
            $route = 'retailer.notifications';
        }

        $allNotifications = Notification::with('order.item.product.thumbnailImage')->where('receiver_id', $user->id)->orderByDesc('id')->paginate($request->global_pagination);

        return view($file, compact('allNotifications'));
    }

    public function downloadProof()
    {
        $path = Storage::url(auth()->user()->documents[0]->file);
        $mime = substr($path, strrpos($path, ".") + 1);
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . basename($path));
        header("Content-Type: ." . $mime);

        return readfile($path);
    }

    public function bankdetail(Request $request)
    {
        // dd($request->toArray());
        $bankDetail = RetailerBankInformation::where('retailer_id', auth()->user()->id)->first();

        if ($request->isMethod('post')) {
            $request->validate([
                'account_holder_first_name' => 'required|regex:/^[A-Za-z]+$/',
                'account_holder_last_name' => 'required|regex:/^[A-Za-z]+$/',
                'account_holder_dob' => 'required',
                //'account_holder_type' => 'required|in:Individual,Company',
                //'account_type' => 'required|in:Custom,Express,Standard',
                'account_number' => 'required|digits_between:10,12',
                'routing_number' => 'required|digits:9',
            ], [
                'account_holder_first_name.required' => 'This field is required.',
                'account_holder_first_name.regex' => 'Account holder first name should not contain any spaces, only alphabets are valid',
                'account_holder_last_name.required' => 'This field is required.',
                'account_holder_last_name.regex' => 'Account holder last name should not contain any spaces, only alphabets are valid',
                'account_holder_dob.required' => 'This field is required.',
                //'account_holder_type.required' => 'Please select the account holder type',
                //'account_holder_type.in' => 'Account holder type can be Individual or Company',
                //'account_type.required' => 'Please select the account type',
                //'account_type.in' => 'Account type can be Custom, Express or Standard',
                'account_number.required' => 'This field is required.',
                'account_number.digits_between' => 'Account number should be of 10 to 12 digits',
                'routing_number.required' => 'This field is required.',
                'routing_number.digits' => 'Routing number must be of 9 digits',
            ]);

            try {
                $accountHolderName = $request->account_holder_first_name . ' ' . $request->account_holder_last_name;
                $birthDate = date($request->global_date_format_for_check, strtotime($request->account_holder_dob));

                $dobFormat = DateTime::createFromFormat($request->global_date_format_for_check, $birthDate)->format('Y-m-d');
                $dob = explode('-', $dobFormat);
                $stripe = new Stripe\StripeClient(env('STRIPE_SECRET'));
                $response = $stripe->tokens->create([
                    'bank_account' => [
                        'country' => 'US',
                        'currency' => 'usd',
                        'account_holder_name' => $accountHolderName,
                        'account_holder_type' => 'Individual',
                        'routing_number' => $request->routing_number,
                        'account_number' => $request->account_number,
                    ],
                ]);

                $customer = $stripe->customers->create([
                    'email' => auth()->user()->email,
                    'name' => auth()->user()->name,
                ]);

                // $accountPostData = [
                //     "type" => strtolower($request->account_type),
                //     "country" => 'US',
                //     "email" => auth()->user()->email,
                //     "business_type" => strtolower($request->account_holder_type),
                //     'business_profile' => [
                //         'url' => url('https://chere.shinedezign.pro')
                //     ],
                //     "capabilities" => [
                //         "transfers" => [
                //             "requested" => "true"
                //         ],
                //     ],
                //     "tos_acceptance" => [
                //         "date" => time(),
                //         "ip" => $response['client_ip']
                //     ],
                //     "external_account" => $response["id"]
                // ];

                // if ('individual' == strtolower($request->account_holder_type)) {
                //     $accountPostData["individual"] = [
                //         "dob" => [
                //             "day" => $dob[2],
                //             "month" => $dob[1],
                //             "year" => $dob[0],
                //         ],
                //         "email" => auth()->user()->email,
                //         "first_name" => $request->account_holder_first_name,
                //     ];
                // } else {
                //     $accountPostData["company"] = [
                //         "address" => 'US',
                //         "name" => $request->account_holder_first_name.' '.$request->account_holder_last_name,
                //     ];
                // }

                $accountPostData = [
                    "type" => "custom",
                    "country" => "US",
                    "email" => auth()->user()->email,
                    "business_type" => strtolower('Individual'),
                    "individual" => [
                        "dob" => [
                            "day" => $dob[2],
                            "month" => $dob[1],
                            "year" => $dob[0],
                        ],
                        "email" => auth()->user()->email,
                        "first_name" => $request->account_holder_first_name,
                        "last_name" => $request->account_holder_last_name,
                    ],
                    "capabilities" => [
                        "transfers" => [
                            "requested" => "true"
                        ],
                    ],
                    "tos_acceptance" => [
                        "date" => time(),
                        "ip" => $response['client_ip']
                    ],
                    "business_profile" => [
                        "url" => url('https://chere.shinedezign.pro')
                    ],
                    "external_account" => $response["id"]
                ];

                $accountCreate = $stripe->accounts->create([
                    $accountPostData
                ]);

                if (isset($response['id']) && isset($accountCreate['id']) && isset($customer['id'])) {
                    $user = User::find(auth()->user()->id);
                    // $user->customer_id = $customer['id'];
                    $user->save();
                    Auth::setUser($user);
                    $bankDetail = RetailerBankInformation::updateOrCreate([
                        'retailer_id' => auth()->user()->id,
                    ], [
                        'account_holder_first_name' => $request->account_holder_first_name,
                        'account_holder_last_name' => $request->account_holder_last_name,
                        'account_holder_dob' => $dobFormat,
                        'account_holder_type' => 'Individual',
                        'account_type' => 'Custom',
                        'account_number' => jsencode_userdata($request->account_number),
                        'routing_number' => jsencode_userdata($request->routing_number),
                        'is_verified' => ($accountCreate->payouts_enabled) ? 'Yes' : 'No',
                        'stripe_btok_token' => jsencode_userdata($response['id']),
                        'stripe_ba_token' => jsencode_userdata($response['bank_account']->id),
                        'stripe_account_token' => jsencode_userdata($accountCreate->id),
                        'stripe_btok_token_response' => jsencode_userdata($response),
                        'stripe_account_token_response' => jsencode_userdata($accountCreate),
                    ]);
                }

                $product = Product::where('user_id', auth()->user()->id)->first();
                if ($product) {
                    $product->update([
                        'status' => '1'
                    ]);
                }

                if ($request->checkbankdetails == 1) {
                    return redirect()->route('edit-account', ['tab' => 'nav-bankdetails'])->with(['message' => "Bank details updated successfully"]);
                } else {
                    return response()->json([
                        'success'    =>  true,
                        'message'   => "Bank details updated successfully"
                        // 'url'       =>   $url
                    ], 200);
                }
            } catch (Exception $ex) {
                if ($request->checkbankdetails == 1) {
                    return redirect()->back()->with('message', $ex->getMessage());
                }
                // dd($ex->getMessage());
                $status = "warning";
                $message = $ex->getMessage();
                $error = view('components.stripe_error', compact('status', 'message'))->render();
                return response()->json(['message' => $ex->getMessage(), 'error' => $error]);
                // return redirect()->back()->with('error', "Error in this file: " . $ex->getFile() . " line no " . $ex->getLine() . " " . $ex->getMessage())->withInput($request->input());
            }
        }

        return view('customer.edit_profile', compact('bankDetail'));
    }

    public function removeProfile()
    {
        if (!is_null(auth()->user()->profile_pic_url)) {
            Storage::disk('s3')->delete('profiles/' . auth()->user()->profile_pic_file);
            $userId = Auth::user()->id;
            $user = User::find($userId);
            $user->profile_pic_file = null;
            $user->profile_pic_url = null;
            $user->save();
            Auth::setUser($user);

            return redirect()->back()->with('success', 'Your profile image has been removed successfully');
        }

        return redirect()->back();
    }
    public function identification(Request $request)
    {
        // dd($request->toArray());
        $request->validate(
            [
                'proof' => 'required'
            ],
            [
                'proof.required' => 'This field is required.',
            ]
        );
        try {
            $user = auth()->user();
            $userDocument = UserDocuments::where('user_id', $user->id)->first();

            if ($request->hasFile('proof')) {

                $proof = store_image($request->file('proof'), 'admin/proof');
                if ($proof != null) {
                    $uploadedFileName = $request->file('proof')->getClientOriginalName();
                    auth()->user()->documents()->create([
                        'file' => $proof['name'],
                        'url' => $proof['url'],
                        'uploaded_file_name' => $uploadedFileName,
                    ]);
                }

                if (isset($userDocument) && !is_null($userDocument->file) && Storage::exists($userDocument->file)) {
                    Storage::delete($userDocument->file);
                }
            }
            return response()->json([
                'success'    =>  true,
                // 'url'       =>   $url
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success'    =>  false,
                'msg'      =>  $e->getMessage()
            ]);
        }
    }

    // html blade file change password

    public function ChangePassword()
    {
        return view('customer.change_password');
    }

    public function changeProfile(User $user,Request $request)
    {
        $bank = $request->input('bank') ?? null;
        return view('customer.change_profile', compact('user','bank'));
    }

    public function saveUserprofile(Request $request)
    {
        
        try {
            $user = auth()->user();
            $data = [
                'username' => $request->name,
                'name' => $request->name,
                // 'email' => $request->email,
            ];

            if ($request->hasFile('profile_pic')) {
                if (!is_null($user->profile_file)) {
                    Storage::disk('public')->delete($user->profile_file);
                }
            
                $file = $request->file('profile_pic');
                $path = $file->store('profiles', 'public');
                $data['profile_file'] = $path;
            }

            // $user->update($data);
            // if ($request->email !== $user->email) {
            //     unset($data['email']);
            // }

            $userdetail = [
                'address1' => $request->complete_address,
                'about' => $request->about ?? null,
            ];

             RetailerBankInformation::where('retailer_id', auth()->user()->id)->first();

            RetailerBankInformation::updateOrCreate([
                'retailer_id' => auth()->user()->id,
            ], [
                'account_holder_first_name' => $request->account_holder_first_name,
                'account_holder_last_name' => $request->account_holder_last_name,
                'account_holder_dob' => $request->input('date_of_birth'),
                'account_holder_type' => 'Individual',
                'account_type' => 'Custom',
                'account_number' => jsencode_userdata($request->account_number),
                'routing_number' => jsencode_userdata($request->routing_number),
                'is_verified' => "" ,
                'stripe_btok_token' => "",
                'stripe_ba_token' => "",
                'stripe_account_token' => "",
                'stripe_btok_token_response' => "",
                'stripe_account_token_response' => "",
            ]);
            $user->update($data);

            if ($user->userDetail) {
                $user->userDetail()->update($userdetail);
            }

            return redirect()->route('edit-account')->with('success', __('user.messages.profileUpdated'));
            // return redirect()->back()->with('success', __('user.messages.profileUpdated'));
            // return redirect()->route('index')->with('success', __('user.messages.profileUpdated'));
        } catch (Exception $exception) {
            return redirect()->back();
        }
    }
    

}
