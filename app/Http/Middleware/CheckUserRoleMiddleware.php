<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\{Role, User};

class CheckUserRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $userId = $request->route()->parameters()['user']->id;
        $customerRouteUri = ['admin/customer/{user}/view', 'admin/customer/{user}/approve', 'admin/customer/{user}/proof/download'];
        $vendorRouteUri = ['admin/vendor/{user}/view', 'admin/vendor/{user}/approve', 'admin/vendor/{user}/proof/download'];
        if (in_array($request->route()->uri, $customerRouteUri)) {
            $roleId = Role::where('name', 'customer')->pluck('id')->first();
            $customer = User::where('role_id', $roleId)->where('id', $userId)->first();
            
            if (is_null($customer)) {
                return redirect()->route('admin.customers')->with('message', 'Unable to found the customer');
            }

        } elseif (in_array($request->route()->uri, $vendorRouteUri)) {
            $role = Role::whereIn('name', ['retailer', 'individual'])->get()->pluck('id')->toArray();
            $vendor = User::whereIn('role_id', $role)->where('id', $userId)->first();

            if (is_null($vendor)) {
                return redirect()->route('admin.vendors')->with('message', 'Unable to found the vendor');
            }
        }

        return $next($request);
    }
}
