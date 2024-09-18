<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;
use Illuminate\Contracts\Queue\ShouldQueue; // Add this import

class Order extends Model implements ShouldQueue // Implement ShouldQueue
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'retailer_id',
        'query_id',
        'product_id',
        'transaction_id',
        'from_date',
        'to_date',
        'from_hour',
        'from_minute',
        'order_date',
        'cancelled_date',
        'pickedup_date',
        'returned_date',
        'promocode',
        'discount_type',
        'discount_percentage',
        'discounted_amount',
        'subtotal',
        'tax',
        'taxrate',
        'total',
        'status',
        'customer_confirmed_pickedup',
        'retailer_confirmed_pickedup',
        'customer_confirmed_returned',
        'retailer_confirmed_returned',
        'cancellation_note',
        'dispute_status',
        'dispute_date'
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
        return $this->belongsTo(User::class)->withTrashed();
    }
    public function retailer()
    {
        return $this->belongsTo(User::class, 'retailer_id');
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

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function customerquery()
    {
        return $this->belongsTo(Query::class, 'query_id');
    }
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

    public function queryOf()
    {
        return $this->hasOne(Query::class, "id", "query_id");
    }

    public static function countCompletedOrders($retailerId)
    {
        return self::where('retailer_id', $retailerId)
            ->where('status', 'Completed')
            ->count();
    }

    public static function countOtherOrders($retailerId)
    {
        return self::where('retailer_id', $retailerId)
            ->where(function ($query) {
                $query->where('status', 'Waiting')
                    ->orWhere('status', 'Picked Up');
            })
            ->where('dispute_status', '!=', 'yes') // Exclude if disputed_status is 'yes'
            ->count();
    }

    public function retailePayout()
    {
        return $this->hasOne(RetailerPayout::class, 'order_id', 'id');
    }
}
