<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductUnavailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'order_id', 'from_date', 'to_date', 'from_hour', 'from_minute', 'to_hour', 'to_minute'
    ];

}
