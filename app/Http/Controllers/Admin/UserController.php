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
use App\Models\ReportedProduct;
use App\Models\ReportedProfile;
use App\Models\State;
use App\Models\UserDetail;
use App\Notifications\CustomerImageUpload;
use App\Notifications\DeleteUser;
use Illuminate\Http\Request;
use App\Models\{BillingToken, Order, ProductImage, ProductLocation, Role, User};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;


class UserController extends Controller
{
    // public function customers(Request $request)
    // {
    //     // Ensure global_pagination is set in the request, default to 15 if not present
    //     $pagination = $request->input('global_pagination', 15);

    //     // Get the role ID for customers
    //     $roleId = Role::where('name', 'customer')->pluck('id')->first();

    //     // Fetch customers with their order items and paginate the results
    //     $customers = User::with('orderitem')
    //         ->where('role_id', $roleId)
    //         ->orderBy('id', 'DESC')
    //         ->paginate($pagination);

    //     // Prepare serial number
    //     $sno = (($customers->currentPage() * $customers->perPage()) - $pagination) + 1;

    //     // Fetch orders for each customer
    //     foreach ($customers as $customer) {
    //         $customer->orders = Order::where('retailer_id', $customer->id)->get();
    //         // dd($customer->orders);
    //     }

    //     $active = "customers";

    //     return view('admin.customer.customer_list', compact('customers', 'active', 'sno'));
    // }

    public function customers(Request $request)
    {
        // Ensure global_pagination is set in the request, default to 15 if not present
        $pagination = $request->input('global_pagination', 15);

        // Get the role ID for customers
        $roleId = Role::where('name', 'customer')->pluck('id')->first();

        // Start the query for fetching customers
        $query = User::with('orderitem')
            ->where('role_id', $roleId)
            ->orderBy('id', 'DESC');

        // Apply search filter if search input is present
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm, $request) {
                if ($request->input('SearchVia')) {
                    // Search by email
                    $q->where('email', 'like', '%' . $searchTerm . '%');
                } else {
                    // General search (you can customize this further)
                    $q->where('name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('email', 'like', '%' . $searchTerm . '%');
                }
            });
        }

        // Apply status filter if filter_by_status input is present
        if ($request->filled('filter_by_status')) {
            $status = $request->input('filter_by_status');
            $query->where('status', $status);
        }

        // Paginate the results
        $customers = $query->paginate($pagination);

        // Prepare serial number
        $sno = (($customers->currentPage() * $customers->perPage()) - $pagination) + 1;

        // Fetch orders for each customer
        foreach ($customers as $customer) {
            $customer->orders = Order::where('retailer_id', $customer->id)->get();
        }

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
        if (isset($user->userDetail->country)) {
            $selectedCountryName = $user->userDetail->country;
            $selectedStateName = $user->userDetail->state;
            // dd($selectedStateName);
        } else {
            $selectedCountryName = Country::where('iso_code', 'US')->pluck('id')->first();
            $selectedStateName = State::where('country_id', '231')->first();
        }
        $countries = Country::all();
        // dd($selectedCountryName);
        $selectedCountry = Country::where('name', $selectedCountryName)->orWhere('id', $selectedCountryName)->first();
        $states = State::where('country_id', $selectedCountry->id)->orWhere('id', $selectedStateName)->get();


        // $state = State::where('name',$selectedStateName)->orWhere('id',$selectedStateName->id)->first();
        if (is_string($selectedStateName)) {
            $state = State::where('name', $selectedStateName)->first();
        } else {
            $state = State::where('id', $selectedStateName->id)->first();
        }
        $cities = City::where('state_id', $state->id)->get();
        $notAvailable = 'N/A';
        // if ($user->role->name == 'customer') {
        $file = 'admin.customer.edit';
        // }
        // else {
        // $file = 'admin.retailer.edit';
        // }
        // dd($selectedCountry);

        // dd($state->name);
        return view($file, compact('user', 'selectedCountryName', 'countries', 'states', 'cities', 'notAvailable', 'state'));
    }

    public function update_profile(UserDetailRequest $request, User $user)
    {
        // dd("here",$request->all());
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
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'about' => $request->about
        ];

        // if (auth()->user()->role->name == 'admin') {
        //     $data['email'] = $request->email;
        // }


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


        return redirect()->route('admin.customers')->with('success', 'User profile updated successfully');;
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
            $user = User::findOrFail($id);

            $products = Product::where('user_id', $id)->get();
            foreach ($products as $product) {
                $product->locations()->delete();

                foreach ($product->allImages as $image) {
                    Storage::disk('public')->delete($image->file_path);
                    $image->delete();
                }

                $product->delete();
            }
            UserDetail::where('user_id', $id)->delete();
            // dd($user);

            // $user->notify(new DeleteUser($user));

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


    public function ReportedProducts()
    {
        $products = ReportedProduct::select('product_id', DB::raw('count(*) as occurrences'))
            ->groupBy('product_id')
            ->with('product')
            ->get();

        return view('admin.reportedProducts', compact('products'));
    }

    public function reportedUser()
    {
        $users = ReportedProfile::select('reported_id', DB::raw('count(*) as occurrences'))
            ->groupBy('reported_id')
            ->with('user')
            ->get();

        return view('admin.reportedUser', compact('users'));
    }
}
