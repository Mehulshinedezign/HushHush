<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRetailerBankInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retailer_bank_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retailer_id')->constrained('users');
            $table->string('stripe_bank_id', 100)->nullable();
            $table->string('account_holder_name', 100);
            $table->enum('account_type', ['Custom', 'Express', 'Standard']);
            $table->string('account_number');
            $table->string('routing_number');
            $table->char('currency', 3)->default('usd');
            $table->char('country', 2)->default('US');
            $table->longText('gateway_response')->nullable();
            $table->enum('is_verified', ['Yes', 'No'])->default('No');
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
        Schema::dropIfExists('retailer_bank_information');
    }
}
