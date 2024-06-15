<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetailerPayout extends Model
{
    use HasFactory;

    protected $fillable = [ "retailer_id", "transaction_id", "order_id", "amount", "gateway_response" ];

    public function getOrderIdAttribute($value)
    {
        return explode(",", $value);
    }

    public function retailerDetails(){
        return $this->belongsTo(User::class,'retailer_id');
    }

}
