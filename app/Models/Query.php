<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'product_id', 'for_user', 'query_message', 'status', 'date_range','negotiate_price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function forUser()
    {
        return $this->belongsTo(User::class, 'for_user');
    }
}
