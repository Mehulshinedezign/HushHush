<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your admin. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('dashboard', [App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');
    Route::post('menu-setup', [App\Http\Controllers\AjaxController::class, 'menuSetup'])->name('menusetup');
    Route::match(['GET', "POST"], 'status', [App\Http\Controllers\AjaxController::class, 'toggleStatus'])->name('status');

    // Profile
    Route::get('profile', [App\Http\Controllers\ProfileController::class, 'profile'])->name('profile');
    Route::post('save-user-detail', [App\Http\Controllers\ProfileController::class, 'saveUserDetail'])->name('saveuserdetail');
    Route::post('update-password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('updatepassword');

    // Category routes
    Route::get('categories', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories');
    Route::get('categories/add', [App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('addcategory');
    Route::post('categories', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('savecategory');
    Route::get('categories/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'show'])->name('viewcategory');
    Route::get('categories/{category}/edit', [App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('editcategory');
    Route::post('categories/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('updatecategory');
    Route::get('categories/{category}/delete', [App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('deletecategory');
    Route::get('categories/{category}/products', [App\Http\Controllers\Admin\CategoryController::class, 'products'])->name('categoryproduct');
    Route::get('categories/{category}/product/{product}', [App\Http\Controllers\Admin\CategoryController::class, 'editProduct'])->name('editproduct');
    Route::post('categories/{category}/product/{product}', [App\Http\Controllers\Admin\CategoryController::class, 'updateProduct'])->name('updateproduct');

    // User routes
    Route::get('customers', [App\Http\Controllers\Admin\UserController::class, 'customers'])->name('customers');

    // Brand routes
    Route::get('brand/list', [App\Http\Controllers\Admin\BrandController::class, 'index'])->name('brand');
    Route::get('brand/add', [App\Http\Controllers\Admin\BrandController::class, 'create'])->name('addbrand');
    Route::post('brand', [App\Http\Controllers\Admin\BrandController::class, 'store'])->name('savebrand');
    // Route::post('view/brand', [App\Http\Controllers\Admin\BrandController::class, 'viewBrand'])->name('viewbrand');
    Route::get('brand/{brand}/edit', [App\Http\Controllers\Admin\BrandController::class, 'edit'])->name('editbrand');
    Route::post('brand/{brand}', [App\Http\Controllers\Admin\BrandController::class, 'update'])->name('updatebrand');
    Route::get('brand/{brand}/delete', [App\Http\Controllers\Admin\BrandController::class, 'destroy'])->name('deletebrand');


    Route::get('customers_export', [App\Http\Controllers\Admin\UserController::class, 'get_customers_data'])->name('customers.export');
    Route::get('vendors', [App\Http\Controllers\Admin\UserController::class, 'vendors'])->name('vendors');
    Route::get('user/{user}/status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('changestatus');
    Route::middleware('userrole')->group(function () {
        Route::get('customer/{user}/view', [App\Http\Controllers\Admin\UserController::class, 'viewCustomer'])->name('viewcustomer');
        Route::get('customer/{user}/approve', [App\Http\Controllers\Admin\UserController::class, 'approveCustomer'])->name('approvecustomer');
        Route::get('customer/{user}/proof/download', [App\Http\Controllers\Admin\UserController::class, 'downloadProof'])->name('downloadproof');
        Route::get('vendor/{user}/view', [App\Http\Controllers\Admin\UserController::class, 'viewVendor'])->name('viewvendor');
        Route::get('vendor/{user}/approve', [App\Http\Controllers\Admin\UserController::class, 'approveVendor'])->name('approvevendor');
        Route::get('vendor/{user}/proof/download', [App\Http\Controllers\Admin\UserController::class, 'downloadProof'])->name('vendor.downloadproof');
    });
    Route::get('vendor/{user}/product', [App\Http\Controllers\Admin\UserController::class, 'VendorProductList'])->name('vendor-product-list');
    Route::get('vendor/edit/product/{product}', [App\Http\Controllers\Admin\UserController::class, 'vendorproductedit'])->name('vendor-product-edit');
    Route::post('update/product/{product}', [App\Http\Controllers\Admin\UserController::class, 'update_product'])->name('update.product');
    Route::get('vendor/product/{product}/delete', [App\Http\Controllers\Admin\UserController::class, 'productdestroy'])->name('deleteproduct');

    Route::get('view/product/{product}', [App\Http\Controllers\Admin\UserController::class, 'view_product'])->name('view_product');
    Route::get('user/{id}', [App\Http\Controllers\Admin\UserController::class, 'delete_user'])->name('delete.user');
    Route::get('edit/{user}', [App\Http\Controllers\Admin\UserController::class, 'edit_user'])->name('edit.user');
    Route::post('update/{user}', [App\Http\Controllers\Admin\UserController::class, 'update_profile'])->name('update.user');

    //Commission
    Route::match(["GET", "POST"], "commission", [App\Http\Controllers\AdminController::class, 'commission'])->name("commission");

    //CMS
    Route::get('cms', [App\Http\Controllers\AdminController::class, 'cms'])->name("cms");
    Route::get('cms-page/{page}/edit', [App\Http\Controllers\AdminController::class, 'editCms'])->name("editcms");
    Route::post('cms-page/{page}/save', [App\Http\Controllers\AdminController::class, 'saveCms'])->name("savecms");
    Route::post('cms-upload/{page}/save', [App\Http\Controllers\AdminController::class, 'upload'])->name("uploadcms");

    //Customer Security Payouts
    Route::get("view-customer-completed-orders/{customer}", [App\Http\Controllers\AdminController::class, 'viewCustomerCompletedOrders'])->name("view-customer-completed-orders");
    Route::post("pay-security-to-customer/{customer}", [App\Http\Controllers\AdminController::class, 'paySecurityToCustomer'])->name("pay-security-to-customer");

    //Retailer Bank Information & Payouts
    Route::get("view-retailer-completed-orders/{retailer}", [App\Http\Controllers\AdminController::class, 'viewRetailerCompletedOrders'])->name("view-retailer-completed-orders");
    Route::post("pay-to-retailer/{retailer}", [App\Http\Controllers\AdminController::class, 'payToRetailer'])->name("pay-to-retailer");

    //global setting
    Route::get('settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings');
    Route::get('settings/{setting}/edit', [App\Http\Controllers\Admin\SettingController::class, 'edit'])->name('editsetting');
    Route::post('settings/{setting}', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('updatesetting');
    Route::get('remove-profile', [App\Http\Controllers\ProfileController::class, 'removeProfile'])->name('removeprofile');

    //Transaction
    Route::get("transaction", [App\Http\Controllers\AdminController::class, 'transaction'])->name("transaction");
    Route::get("retailer-payouts", [App\Http\Controllers\AdminController::class, 'retailerPayouts'])->name("retailer-payouts");
    Route::get("disputed-payouts", [App\Http\Controllers\AdminController::class, 'disputedPayouts'])->name("disputed-payouts");
    Route::get("security-payouts", [App\Http\Controllers\AdminController::class, 'securityPayouts'])->name("security-payouts");
    Route::get('payouts_export', [App\Http\Controllers\AdminController::class, 'payouts_export'])->name('payouts_export.export');


    //Orders
    Route::get("orders", [App\Http\Controllers\AdminController::class, 'orders'])->name("orders");
    Route::get("view-order/{order}", [App\Http\Controllers\AdminController::class, 'viewOrder'])->name("view-order");
    Route::get("disputed-orders", [App\Http\Controllers\AdminController::class, 'disputedOrders'])->name("disputed-orders");
    Route::post("resolve-dispute-order/{order}", [App\Http\Controllers\AdminController::class, 'resolveDisputeOrder'])->name("resolve-dispute-order");


    Route::get("reported/products", [App\Http\Controllers\Admin\UserController::class, 'ReportedProducts'])->name("reportedProducts");



});
