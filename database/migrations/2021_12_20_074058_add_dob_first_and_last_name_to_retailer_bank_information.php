<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDobFirstAndLastNameToRetailerBankInformation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('retailer_bank_information', function (Blueprint $table) {
            $table->dropColumn(['account_holder_name']);
            $table->string('account_holder_first_name', 100)->after('retailer_id');
            $table->string('account_holder_last_name', 100)->after('account_holder_first_name');
            $table->date('account_holder_dob')->after('account_holder_last_name');
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
            $table->string('account_holder_name', 100)->after('retailer_id');
            $table->dropColumn(['account_holder_first_name', 'account_holder_last_name', 'account_holder_dob']);
        });
    }
}
