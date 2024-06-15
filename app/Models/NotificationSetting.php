<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'order_placed', 'order_pickup', 'order_return', 'order_cancelled', 'payment', 'welcome_mail', 'feedback', 'user_booking_request', 'lender_accept_booking_request', 'reminder_for_pickup_time_location', 'reminder_for_drop_off_time_location', 'rate_your_experience', 'item_we_think_you_might_like', 'lender_receives_booking_request', 'lender_send_renter_first_msg', 'renter_send_lender_first_msg', 'reminder_to_start_listing_items'
    ];

    /**
     * User
     *
     * @var object
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
