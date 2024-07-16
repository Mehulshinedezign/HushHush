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
            $table->renameColumn('custom_address', 'pick_up_location');
            $table->renameColumn('map_address', 'product_complete_location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_locations', function (Blueprint $table) {
            $table->renameColumn('pick_up_location', 'custom_address');
            $table->renameColumn('product_complete_location', 'map_address');
        });
    }
};
