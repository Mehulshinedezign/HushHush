<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportedProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'reported_id',
        'user_id',

    ];

    public function user()
    {
        return $this->belongsTo(User::class,'reported_id','id');
    }

    // public function reportedUser()
    // {
    //     return $this->belongsTo(User::class,'reported_id','id');
    // }

    
}
