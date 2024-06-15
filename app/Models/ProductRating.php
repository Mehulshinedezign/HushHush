<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id','order_id', 'user_id','rating','review'
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
     * User details
     *
     * @var object
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
