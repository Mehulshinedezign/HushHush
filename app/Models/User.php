<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\PasswordReset;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'name',
        'username',
        'email',
        'phone_number',
        'password',
        'profile_file',
        'profile_url',
        'status',
        'is_approved',
        'zipcode',
        'email_verification_token',
        'otp_is_verified'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $appends = ['frontend_profile_url'];

    protected function frontendProfileUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (filter_var($this->profile_url, FILTER_VALIDATE_URL))
                    return $this->profile_url;
                // if( $this->provider )
                //     return $this->profile_url;

                return $this->profile_url ?
                    Storage::url($this->profile_url) :
                    asset('img/avatar-small.png');
            }
        );
    }

    public function userDetail()
    {
        return $this->hasOne(UserDetail::class);
    }

    /**
     * User documents
     *
     * @var object
     */

    public function documents()
    {
        return $this->hasMany(UserDocuments::class);
    }

    /**
     * User notification setting
     *
     * @var object
     */

    public function notification()
    {
        return $this->hasOne(NotificationSetting::class);
    }

    /**
     * User role
     *
     * @var object
     */

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function vendorBankDetails()
    {
        return $this->hasOne(RetailerBankInformation::class, 'retailer_id');
    }

    public function vendorCompletedOrderedItems()
    {
        return $this->hasMany(OrderItem::class, 'retailer_id')->where([
            ["status", "=", "Completed"],
            ["vendor_received_amount", "!=", NULL],
        ]);
    }

    public function vendorPayout()
    {
        return $this->hasMany(RetailerPayout::class, 'retailer_id');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordReset($token));
    }
    public function scopeSearch($query)
    {
        $request = request();
        $query->when(!empty($request->filter_by_name), function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->filter_by_name . '%');
        })->when(!is_null($request->dates), function ($q) use ($request) {
            $dates = explode(' - ', $request->dates);
            $dateFrom = $dates[0];
            $dateTo = $dates[1];
            $q->where(function ($query) use ($dateFrom, $dateTo) {
                $query->whereDate('created_at', '>=', $dateFrom);
                $query->whereDate('created_at', '<=', $dateTo);
            });
        })->when(!is_null($request->filter_by_status), function ($q) use ($request) {
            switch ($request->filter_by_status) {
                case ('Active');
                    $q->where('status', '1');
                    break;
                case ('Inactive');
                    $q->where('status', '0');
                    break;
                default:
                    return;
            }
        })->when(!is_null($request->status) && in_array($request->status, ['approved', 'pending']), function ($q) use ($request) {
            $isApproved = ($request->status == 'approved') ? '1' : '0';
            $q->where('is_approved', $isApproved);
        })->when(!is_null($request->search), function ($q) use ($request) {
            $q->where(function ($query) use ($request) {
                $query->where("name", "like", strtolower($request->search) . "%")->orWhere("email", "like", strtolower($request->search) . "%");
            });
        });
    }

    public function scopeVendorlist($query)
    {
        $request = request();
        $query->when(!is_null($request->status) && in_array($request->status, ['approved', 'pending']), function ($q) use ($request) {
            $isApproved = ($request->status == 'approved') ? '1' : '0';
            $q->where('is_approved', $isApproved);
        })->when(!is_null($request->search), function ($q) use ($request) {
            $q->where(function ($query) use ($request) {
                $query->where("name", "like", strtolower($request->search) . "%")->orWhere("email", "like", strtolower($request->search) . "%");
            });
        })->when(!is_null($request->filter_by_status), function ($q) use ($request) {
            switch ($request->filter_by_status) {
                case ('Active');
                    $q->where('status', '1');
                    break;
                case ('Inactive');
                    $q->where('status', '0');
                    break;
                default:
                    return;
            }
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'user_id');
    }

    // add chat
    public function chat()
    {
        return $this->hasOne(Chat::class, 'user_id');
    }

    public function orderitem()
    {
        return $this->hasMany(OrderItem::class, 'customer_id');
    }

    public function cards()
    {
        return $this->hasMany(UserCard::class);
    }
}
