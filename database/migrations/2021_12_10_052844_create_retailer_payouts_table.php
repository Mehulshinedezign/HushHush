<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRetailerPayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retailer_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retailer_id')->constrained('users');
            $table->string('transaction_id',50);
            $table->string('order_id',50);
            $table->decimal('amount',8,2);
            $table->longText('gateway_response');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('retailer_payouts');
    }
}
