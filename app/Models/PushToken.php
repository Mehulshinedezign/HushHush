<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushToken extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','fcm_token','device_id','device_type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
