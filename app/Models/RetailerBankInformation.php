<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetailerBankInformation extends Model
{
    use HasFactory;

    protected $fillable = [ 
        "retailer_id", "account_holder_first_name", "account_holder_last_name", "account_holder_dob", "account_holder_type", "account_type", "account_number", "routing_number", "currency", "country", "is_verified", "stripe_btok_token", "stripe_ba_token", "stripe_account_token", "stripe_btok_token_response", "stripe_ba_verification_response", "stripe_account_token_response"
    ];

}
