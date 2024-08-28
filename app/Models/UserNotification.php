<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'query_receive',
        'accept_item',
        'reject_item',
        'order_req',
        'customer_order_req',
        'customer_order_pickup',
        'lender_order_pickup',
        'customer_order_return',
        'lender_order_return',
        'order_canceled_by_lender',
        'order_canceled_by_customer',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
