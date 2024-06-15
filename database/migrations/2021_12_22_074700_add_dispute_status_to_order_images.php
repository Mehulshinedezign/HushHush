<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDisputeStatusToOrderImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_images', function (Blueprint $table) {
            $table->dropColumn(['type']);
        });

        Schema::table('order_images', function (Blueprint $table) {
            $table->enum('type', ['pickedup', 'returned', 'disputed'])->after('url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_images', function (Blueprint $table) {
            
        });
    }
}
