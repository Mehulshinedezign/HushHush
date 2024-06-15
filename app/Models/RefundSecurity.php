<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundSecurity extends Model
{
    use HasFactory;

    protected $table = "refund_security";

    protected $fillable = [ 'user_id', 'product_id', 'order_id', 'security_amount', 'paid_amount', 'description', 'status' , 'transaction_id', 'security_return_date', 'gateway_response' ];

    /**
     * Order
     *
     * @var object
     */

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getPaidAmountAttribute($value){
        return number_format($value,2);
    }
}
