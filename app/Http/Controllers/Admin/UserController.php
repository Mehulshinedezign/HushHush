<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CustomersExport;
use App\Exports\PayoutsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserDetailRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\NotificationSetting;
use App\Models\Product;
use App\Models\State;
use App\Models\UserDetail;
use App\Notifications\DeleteUser;
use Illuminate\Http\Request;
use App\Models\{BillingToken, Role, User};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;


class UserController extends Controller
{
    public function customers(Request $request)
    {
        $roleId = Role::where('name', 'customer')->pluck('id')->first();
        $customers = User::with('orderitem')->where('role_id', '!=', '1')->Search()->orderBy('id', 'DESC')->paginate($request->global_pagination);

        $sno = (($customers->currentPage() * $customers->perPage()) - $request->global_pagination) + 1;

        $active = "customers";

        return view('admin.customer.customer_list', compact('customers', 'active', 'sno'));
    }

    public function viewCustomer(User $user)
    {
        return view('admin.customer.customer_view', compact('user'));
    }

    public function edit_user(User $user)
    {
        $user = User::with('userDetail', 'notification')->find($user->id);
        if (isset($user->userDetail->country_id)) {
            $selectedCountryId = $user->userDetail->country_id;
        } else {

            $selectedCountryId = Country::where('iso_code', 'US')->pluck('id')->first();
        }
        $countries = Country::all();
        $states = State::where('country_id', $selectedCountryId)->get();
        $cities = City::where('state_id', $user->state_id)->get();
        $notAvailable = 'N/A';
        // if ($user->role->name == 'customer') {
        $file = 'admin.customer.edit';
        // } 
        // else {
        //     $file = 'admin.retailer.edit';
        // }
        return view($file, compact('user', 'selectedCountryId', 'countries', 'states', 'cities', 'notAvailable'));
    }

    public function update_profile(UserDetailRequest $request, User $user)
    {
        $data = [
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'zipcode' => $request->postcode,
            'password' => $request->password,
        ];

        $userdetail = [
            'user_id' => $user->id,
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
            // $path = $request->file('profile_pic')->store('profiles', 's3');
            // $url = Storage::disk('s3')->url($path);
            // $fileName = basename($path);
            // $data['profile_pic_url'] = $url;
            // $data['profile_pic_file'] = $fileName;
            // Storage::disk('s3')->delete('profiles/'. $user->profile_pic_file);

            $profile = s3_store_image($request->file('profile_pic'), 'admin/profilePicture');
            if ($profile != null) {
                $data['profile_file'] = $profile['name'];
                $data['profile_url'] = $profile['url'];
            }

            if (!is_null($user->profile_url)) {
                Storage::disk('s3')->delete('admin/profilePicture/' . $userDocument->file);
                // Storage::delete($user->profile_url);
            }
        }

        $notifi = [
            'user_id' => $user->id,
            'order_placed' => is_null($request->order_placed) ? 'off' : 'on',
            'order_pickup' => is_null($request->order_pickup) ? 'off' : 'on',
            'order_return' => is_null($request->order_return) ? 'off' : 'on',
            'order_cancelled' => is_null($request->order_cancelled) ? 'off' : 'on',
            'payment' => is_null($request->payment) ? 'off' : 'on',
        ];
        $notification = NotificationSetting::where('id', $user->id)->first();
        if (!is_null($notification)) {
            $notification->update($notifi);
        } else {
            NotificationSetting::create($notifi);
        }
        $userdata = User::with('userDetail', 'notification')->where('id', $user->id)->first();
        $userdata->update($data);

        if ($user->userDetail) {
            $user->userDetail()->update($userdetail);
        } else {
            $user->userDetail()->create($userdetail);
        }


        return redirect()->route('admin.customers');
    }
    public function approveCustomer(User $user)
    {
        $user->update(['is_approved' => '1']);

        return redirect()->back()->with('message', 'Customer account has been verified successfully');
    }

    public function vendors(Request $request)
    {
        $active = "vendors";
        $roleId = Role::whereIn('name', ['retailer', 'individual'])->pluck('id')->toArray();
        if (isset($request->type)) {
            $roleId = Role::where('name', $request->type)->pluck('id')->toArray();
        }
        $vendors = User::with('role', 'products')->Vendorlist()->whereIn('role_id', $roleId)->orderBy('id', 'DESC')->paginate($request->global_pagination);
        $sno = (($vendors->currentPage() * $vendors->perPage()) - $request->global_pagination) + 1;
        // $Products = Product::with('retailer')->get();
        // dd($Products);
        return view('admin.retailer.vendor_list', compact('vendors', 'active', 'sno'));
    }

    public function viewVendor(User $user)
    {
        return view('admin.retailer.vendor_view', compact('user'));
    }

    public function approveVendor(User $user)
    {
        $user->update(['is_approved' => '1']);

        return redirect()->back()->with('message', 'Vendor account has been verified successfully');
    }

    public function VendorProductList(Request $request, User $user)
    {
        //    $user = auth()->user();
        // dd($user);
        $products = Product::where('user_id', $user->id)->get();
        return view('admin.customer.vendor_product_list', compact('products'));
    }
    public function vendorproductedit(Product $product)
    {
        return view('admin.customer.vendor_edit_product', compact('product'));
    }

    public function view_product(Product $product)
    {
        $product = Product::with('allImages')->where('id', $product->id)->first();

        return view('admin.customer.view_product', compact('product'));
    }
    public function update_product(Request $request, Product $product)
    {
        //  dd($request->toArray());
        $user =  User::where('id', $product->user_id)->get();
        $data = [
            'name' => $product->name,
            'description' => $product->description,
            'rentaltype' => $product->rentaltype,
            // 'category_id' => jsdecode_userdata($product->category_id),
            'subcat_id' => $product->sub_cat,
            'size' => $product->size,
            'color' => $product->color,
            'condition' => $product->product_condition,
            'user_id' => $product->user_id,
            'rent' => $product->rent,
            'price' => $product->price,
            'status' => $request->status == 1 ? '1' : '0'
        ];
        $product->update($data);
        return redirect()->route('admin.vendor-product-list', ['user' => $product->user_id]);
    }
    public function productdestroy($id)
    {
        Product::where('id', $id)->delete();
        return redirect()->back()->with('message', 'Product successfully deleted');
    }
    public function toggleStatus(User $user)
    {
        $status = ($user->status == '1') ? '0' : '1';
        $user->update(['status' => $status]);

        return response()->json(['title' => 'Success', 'message' => 'Status changed successfully']);
    }

    public function downloadProof(User $user)
    {
        $path = Storage::disk('s3')->url("admin/proof/" . $user->documents[0]->file);
        $mime = substr($path, strrpos($path, ".") + 1);
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . basename($path));
        header("Content-Type: ." . $mime);

        return readfile($path);
    }

    public function delete_user($id)
    {
        try {
            DB::beginTransaction();
            // BillingToken::where('user_id', $id)->delete();
            // UserDetail::where('user_id', $id)->delete();
            // $products = Product::where('user_id', $id)->get();
            // if ($products) {
            //     foreach ($products as $product) {
            //         $product->delete();
            //     }
            // }
            $notification = NotificationSetting::where('user_id', $id)->first();
            $notification->delete();
            $user = User::where('id', $id)->firstOrFail();
            $user->notify(new DeleteUser($user));
            // if ($user->profile_url != null) {
            //     Storage::delete($user->profile_url);
            // }
            // if ($user->cover_url != null) {
            //     Storage::delete($user->cover_url);
            // }
            // $user->update(['deleted_by' => "admin" ]);

            $user->delete();
            DB::commit();
            return redirect()->back()->with('success', 'User deleted successfully');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function get_customers_data()
    {
        return Excel::download(new CustomersExport, 'customers.xlsx');
    }
}
