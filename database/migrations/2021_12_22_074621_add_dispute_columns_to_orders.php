<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDisputeColumnsToOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['status']);
        });
    
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['Waiting', 'Picked Up', 'Completed', 'Cancelled'])->after('total')->default('Waiting');
            $table->dateTime('dispute_date')->after('status')->nullable();
            $table->enum('dispute_status', ['No', 'Yes', 'Resolved'])->after('dispute_date')->default('No');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['dispute_status', 'dispute_date']);
        });
    }
}
