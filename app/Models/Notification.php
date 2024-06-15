<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'sender_id', 'receiver_id', 'order_id', 'action_type', 'message'
    ];

    /**
     * Sender detail
     *
     * @var object
    */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Receiver detail
     *
     * @var object
    */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Order detail
     *
     * @var object
    */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Product category
     *
     * @var object
    */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
