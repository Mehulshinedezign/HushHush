<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDisputedOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dispute_orders', function (Blueprint $table) {
            $table->string("transaction_id",100)->after("reported_by")->nullable();
            $table->enum("refund_type",["Full","Security","Insurance"])->after("transaction_id")->nullable();
            $table->decimal("refund_amount")->after("refund_type")->nullable();
            $table->enum("resolved_status",["Cancelled","Completed"])->after("refund_amount")->nullable();
            $table->dateTime("resolved_date")->after("resolved_status")->nullable();
            $table->longText("gateway_response")->after("resolved_date")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dispute_orders', function (Blueprint $table) {
            $table->dropColumn(["transaction_id","refund_type","refund_amount","resolved_status","resolved_date","gateway_response"]);
        });
    }
}
