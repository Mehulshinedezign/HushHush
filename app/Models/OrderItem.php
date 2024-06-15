<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'order_id', 'product_id', 'customer_id', 'retailer_id', 'rent_per_day', 'total_rental_days', 'security_amount', 'date', 'discounted_amount', 'tax', 'taxrate', 'total', 'status', 'dispute_status', 'dispute_date', 'vendor_received_amount', 'location_id', 'country', 'state', 'city', 'map_address', 'latitude', 'longitude', 'postcode', 'custom_address'
    ];

    public function getRentPerDayAttribute($value)
    {
        return number_format((float)$value, 2, '.', '');
    }

    public function getSecurityAmountAttribute($value)
    {
        return number_format((float)$value, 2, '.', '');
    }

    public function getTotalAttribute($value)
    {
        return number_format((float)$value, 2, '.', '');
    }

    public function getVendorReceivedAmountAttribute($value)
    {
        return number_format((float)$value, 2, '.', '');
    }

    /**
     * Retailer orders
     *
     * @var object
     */
    public function scopeRetailerOrders($query)
    {
        return $query->where('retailer_id', auth()->user()->id);
    }

    /**
     * Product
     *
     * @var object
     */

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    /**
     * Order
     *
     * @var object
     */

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Customer
     *
     * @var object
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Retailer
     *
     * @var object
     */

    public function retailer()
    {
        return $this->belongsTo(User::class, 'retailer_id');
    }

    // check role
    public function scopeCheckRole($query, $roleId)
    {
        if ($roleId == '2') {
            $query->whereRetailerId(auth()->user()->id);
        } else {
            $query->whereCustomerId(auth()->user()->id);
        }
    }

    // get sender chat
    public function chat()
    {
        return $this->hasOne(Chat::class, 'order_id', 'order_id')->where('retailer_id', auth()->user()->id)->withdefault();
    }

    // get receiver chat
    public function getchat()
    {
        return $this->hasOne(Chat::class, 'order_id', 'order_id')->where('user_id', auth()->user()->id)->withdefault();
    }

    // search chat list
    public function scopeSearch($query, $search, $role_relation)
    {
        if ($search != 'all') {
            $query->whereHas('product', function ($q)  use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })
                ->orwhere(function ($subQuery) use ($search, $role_relation) {
                    $subQuery->whereHas($role_relation, function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
                });
        }
    }

    public function scopeOrderByStatus($query, $status)
    {
        switch ($status) {
            case 'Pending':
                $query->where('status', 'Pending')->orwhere('status', 'Picked Up');
                break;
            case 'Completed':
                $query->where('status', 'Completed');
                break;
            case 'Cancelled':
                $query->where('status', 'Cancelled');
                break;
            default;
                $query->where('status', 'Pending')->orwhere('status', 'Picked Up');
        }
    }
}
