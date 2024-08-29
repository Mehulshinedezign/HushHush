<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

//use Illuminate\Database\Eloquent\SoftDeletes;

class ProductLocation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id', 'country', 'state', 'city', 'custom_address', 'postcode', 'latitude', 'longitude', 'map_address', 'product_complete_location', 'pick_up_location', 'raw_address','address1','address2','manul_pickup_location','shipment'
    ];

    /**
     * Product details
     *
     * @var object
     */

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
