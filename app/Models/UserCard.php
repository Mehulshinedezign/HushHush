<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCard extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'brand',
        'card_token',
        'fingerprint',
        'exp_month',
        'exp_year',
        'last_digits',
        'is_default',
    ];
}
