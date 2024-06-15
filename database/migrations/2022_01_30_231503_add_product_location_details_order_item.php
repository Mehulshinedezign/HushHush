<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductLocationDetailsOrderItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('location_id')->after('vendor_received_amount')->nullable()->constrained('product_locations');
            $table->string('country')->after('location_id')->nullable();
            $table->string('state')->after('country')->nullable();
            $table->string('city')->after('state')->nullable();
            $table->string('map_address')->after('city')->nullable();
            $table->string('latitude')->after('map_address')->nullable();
            $table->string('longitude')->after('latitude')->nullable();
            $table->string('postcode')->after('longitude')->nullable();
            $table->string('custom_address')->after('postcode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['location_id', 'country', 'state', 'city', 'map_address', 'latitude', 'longitude', 'postcode', 'custom_address']);
        });
    }
}
