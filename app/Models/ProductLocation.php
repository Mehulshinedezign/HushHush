<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class ProductLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id','country','state','city','custom_address','postcode', 'latitude', 'longitude', 'map_address','product_complete_location','pick_up_location','raw_address'
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
