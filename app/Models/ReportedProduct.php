<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportedProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function countOccurrences()
    {
        return self::where('product_id', $this->product_id)->count();
    }
}
