<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id','order_id', 'customer_id', 'retailer_id','rating','review'
    ];

    /**
     * Get the rating
     *
     * @param  string  $value
     * @return string
     */
    public function getRatingAttribute($value)
    {
        return number_format((float)$value, 1, '.', '');
    }

    /**
     * Product details
     *
     * @var object
    */

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Order details
     *
     * @var object
    */

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Retailer details
     *
     * @var object
    */

    public function retailer()
    {
        return $this->belongsTo(User::class, 'retailer_id');
    }

    /**
     * Customer details
     *
     * @var object
    */

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
