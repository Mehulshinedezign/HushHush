<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRefundSecurity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('refund_security', function (Blueprint $table) {
            $table->string("transaction_id",100)->after("order_id")->nullable();
            $table->dateTime("security_return_date")->after("transaction_id")->nullable();
            $table->longText("gateway_response")->after("security_return_date")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('refund_security', function (Blueprint $table) {
            $table->dropColumn(["transaction_id","security_return_date","gateway_response"]);
        });
    }
}
