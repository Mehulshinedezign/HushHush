<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id', 'location_id', 'transaction_id', 'from_date', 'to_date', 'from_hour', 'from_minute', 'to_hour', 'to_minute', 'order_date', 'cancelled_date', 'pickedup_date', 'returned_date', 'promocode', 'discount_type', 'discount_percentage', 'discounted_amount', 'subtotal', 'tax', 'taxrate', 'total', 'status', 'order_status', 'dispute_status', 'dispute_date', 'customer_confirmed_pickedup', 'retailer_confirmed_pickedup', 'customer_confirmed_returned', 'retailer_confirmed_returned', 'security_option', 'security_option_value', 'security_option_type', 'security_option_amount', 'customer_transaction_fee_type', 'customer_transaction_fee_value', 'customer_transaction_fee_amount', 'order_commission_type', 'order_commission_value', 'order_commission_amount', 'vendor_received_amount', 'cancellation_note','product_id','query_id'
    ];

    protected $appends = ['cancellation_time_left'];

    public function getCancellationTimeLeftAttribute()
    {
        $now = date('Y-m-d');
        $today = new DateTime($now);
        $fromDate = new DateTime($this->attributes['from_date']);
        $difference = $today->diff($fromDate)->format("%r%a");

        return $difference;
    }

    public function getTotalAttribute($value)
    {
        return number_format((float)$value, 2, '.', '');
    }

    public function getVendorReceivedAmountAttribute($value)
    {
        return number_format((float)$value, 2, '.', '');
    }

    public function getCustomerTransactionFeeAmountAttribute($value)
    {
        return number_format((float)$value, 2, '.', '');
    }

    public function getSecurityOptionAmountAttribute($value)
    {
        return number_format((float)$value, 2, '.', '');
    }

    public function scopeActiveOrders($query)
    {
        return $query->where('status', 'Pending')->where('user_id', auth()->user()->id)->whereNotNull('transaction_id');
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

    /**
     * Order item
     *
     * @var object
     */

    public function item()
    {
        return $this->hasOne(OrderItem::class);
    }

    /**
     * User
     *
     * @var object
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Transaction
     *
     * @var object
     */

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Product location
     *
     * @var object
     */

    public function location()
    {
        return $this->belongsTo(ProductLocation::class, 'location_id');
    }

    /**
     * Customer order picked up images
     *
     * @var object
     */

    public function customerPickedUpImages()
    {
        return $this->hasMany(OrderImage::class)->where('type', 'pickedup')->where('uploaded_by', 'customer')->orderByDesc('id');
    }

    /**
     * Customer order returned images
     *
     * @var object
     */

    public function customerReturnedImages()
    {
        return $this->hasMany(OrderImage::class)->where('type', 'returned')->where('uploaded_by', 'customer')->orderByDesc('id');
    }

    /**
     * Retailer order picked up images
     *
     * @var object
     */

    public function retailerPickedUpImages()
    {
        return $this->hasMany(OrderImage::class)->where('type', 'pickedup')->where('uploaded_by', 'retailer')->orderByDesc('id');
    }

    /**
     * Retailer order returned images
     *
     * @var object
     */

    public function retailerReturnedImages()
    {
        return $this->hasMany(OrderImage::class)->where('type', 'returned')->where('uploaded_by', 'retailer')->orderByDesc('id');
    }

    /**
     * Order rating
     *
     * @var object
     */

    public function rating()
    {
        return $this->hasOne(ProductRating::class);
    }

    /**
     * Customer order rating
     *
     * @var object
     */

    public function customerRating()
    {
        return $this->hasOne(CustomerRating::class);
    }


    /**
     * Dispute details
     *
     * @var object
     */

    public function disputeDetails()
    {
        return $this->hasOne(DisputeOrder::class);
    }

    /**
     * Dispute Order Images
     *
     * @var object
     */

    public function disputedOrderImages()
    {
        return $this->hasMany(OrderImage::class)->where('type', 'disputed')->orderByDesc('id');
    }

    /**
     * Dispute Order Images
     *
     * @var object
     */

    public function refundSecurityDetails()
    {
        return $this->hasOne(RefundSecurity::class);
    }

    public function billingToken()
    {
        return $this->hasOne(BillingToken::class);
    }

    function scopedatefilter($q, $date)
    {
        // where('from_date', date('Y-m-d'))
        if ($date == 'fromdate') {
            return $q->where('from_date',  date('Y-m-d'));
        } else {
            return $q->where('to_date',  date('Y-m-d'));
        }
    }
}
