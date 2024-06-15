<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerBillingDetails extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name', 'email', 'user_id', 'order_id', 'token_id', 'address1', 'address2', 'phone_number', 'postcode', 'country_id', 'state_id', 'city_id'
    ];

    /**
     * User country
     *
     * @var object
     */

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * User state
     *
     * @var object
     */

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    /**
     * User city
     *
     * @var object
     */

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
