<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','address1','address2','country','state','city','about','complete_address','zipcode','is_default'];

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

      public function users()
      {
        return $this->belongsTo(User::class);
      }
}
