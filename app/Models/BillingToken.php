<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillingToken extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'token', 'user_id', 'order_id', 'product_id', 'location_id', 'map_location', 'map_latitude', 'map_longitude', 'from_date', 'to_date', 'from_hour', 'from_minute', 'to_hour', 'to_minute', 'rent', 'security', 'security_option', 'security_option_value', 'security_option_type', 'security_option_amount', 'customer_transaction_fee_type', 'customer_transaction_fee_value', 'customer_transaction_fee_amount', 'pickup_datetime', 'payment_intent_id'
    ];

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
     * Product
     *
     * @var object
     */

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Product Location
     *
     * @var object
     */

    public function location()
    {
        return $this->belongsTo(ProductLocation::class, 'location_id');
    }
}
