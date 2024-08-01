<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'payment_id', 'user_id', 'payment_method', 'total', 'date', 'status', 'gateway_response'
    ];

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
     * User
     *
     * @var object
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
