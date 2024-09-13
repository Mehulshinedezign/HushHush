<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDisableDate extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'disable_date','added_by'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
