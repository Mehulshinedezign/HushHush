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
        Schema::table('product_locations', function (Blueprint $table) {
            $table->string('address1')->after('product_id')->nullable();
            $table->string('address2')->after('address1')->nullable();
            $table->enum('manul_pickup_location', ['0', '1'])->default(0)->after('address2');
            $table->enum('shipment', ['0', '1'])->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_locations', function (Blueprint $table) {
            $table->dropColumn('address1');
            $table->dropColumn('address2');
            $table->dropColumn('manul_pickup_location');
            $table->dropColumn('shipment');
        });
    }
};
