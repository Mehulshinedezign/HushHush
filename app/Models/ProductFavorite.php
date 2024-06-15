<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFavorite extends Model
{
    use HasFactory;


    /**
     * Product details
     *
     * @var object
     */

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * User details
     *
     * @var object
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
