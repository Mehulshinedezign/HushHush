<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'chatid','message', 'file', 'url', 'order_id', 'user_id', 'retailer_id', 'sent_by','last_msg_datetime','last_msg'
    ];

    public function scopeChat($query, $orderId)
    {
        return $query->where('order_id', $orderId)->orderBy('id', 'ASC');
    }

    /**
     * User
     *
     * @var object
     */

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
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

    /**
     * Order
     *
     * @var object
     */

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
