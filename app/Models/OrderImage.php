<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderImage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'order_id',
        'file',
        'url',
        'type',
        'uploaded_by',
    ];

    public function getUrlAttribute($value)
    {
        return url('/storage/'.$value);
    }
}
