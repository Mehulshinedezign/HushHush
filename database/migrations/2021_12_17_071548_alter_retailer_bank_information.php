<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRetailerBankInformation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('retailer_bank_information', function (Blueprint $table) {
            $table->dropColumn(['stripe_bank_id', 'gateway_response']);
            $table->enum('account_holder_type', ['Individual', 'Company'])->after('account_holder_name');
            $table->string('stripe_btok_token', 100)->after('is_verified')->nullable();
            $table->string('stripe_ba_token', 100)->after('stripe_btok_token')->nullable();
            $table->string('stripe_account_token', 100)->after('stripe_ba_token')->nullable();
            $table->longText('stripe_btok_token_response')->after('stripe_account_token')->nullable();
            $table->longText('stripe_ba_verification_response')->after('stripe_btok_token_response')->nullable();
            $table->longText('stripe_account_token_response')->after('stripe_ba_verification_response')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('retailer_bank_information', function (Blueprint $table) {
            $table->string('stripe_bank_id', 100)->after('retailer_id')->nullable();
            $table->longText('gateway_response')->after('is_verified')->nullable();
            $table->dropColumn(['account_holder_type', 'stripe_btok_token', 'stripe_ba_token', 'stripe_account_token', 'stripe_btok_token_response', 'stripe_ba_verification_response', 'stripe_account_token_response']);
        });
    }
}
