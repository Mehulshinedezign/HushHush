<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('notification_settings', function (Blueprint $table) {
            $table->enum('welcome_mail', ['on', 'off'])->default('on')->after('payment');
            $table->enum('feedback', ['on', 'off'])->default('on')->after('welcome_mail');
            $table->enum('user_booking_request', ['on', 'off'])->default('on')->after('feedback');
            $table->enum('lender_accept_booking_request', ['on', 'off'])->default('on')->after('user_booking_request');
            $table->enum('reminder_for_pickup_time_location', ['on', 'off'])->default('on')->after('lender_accept_booking_request');
            $table->enum('reminder_for_drop_off_time_location', ['on', 'off'])->default('on')->after('reminder_for_pickup_time_location');
            $table->enum('rate_your_experience', ['on', 'off'])->default('on')->after('reminder_for_drop_off_time_location');
            $table->enum('item_we_think_you_might_like', ['on', 'off'])->default('on')->after('rate_your_experience');
            $table->enum('lender_receives_booking_request', ['on', 'off'])->default('on')->after('item_we_think_you_might_like');
            $table->enum('lender_send_renter_first_msg', ['on', 'off'])->default('on')->after('lender_receives_booking_request');
            $table->enum('renter_send_lender_first_msg', ['on', 'off'])->default('on')->after('lender_send_renter_first_msg');
            $table->enum('reminder_to_start_listing_items', ['on', 'off'])->default('on')->after('renter_send_lender_first_msg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notification_settings', function (Blueprint $table) {
            //
        });
    }
};
