<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisputeOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'reported_id','order_id', 'subject', 'description','reported_by', 'transaction_id', 'refund_type', 'refund_amount', 'resolved_status', 'resolved_date', 'gateway_response','status'
    ];

    /**
     * Order details
     *
     * @var object
     */

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
