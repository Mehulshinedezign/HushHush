<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('security_option', ['security', 'insurance'])->after('discounted_amount')->nullable();
            $table->enum('security_option_type', ['Percentage', 'Fixed'])->after('security_option')->nullable();
            $table->decimal('security_option_value')->after('security_option_type')->nullable();
            $table->decimal('security_option_amount')->after('security_option_value')->nullable();
            
            $table->enum('customer_transaction_fee_type', ['Percentage', 'Fixed'])->after('security_option_amount')->nullable();
            $table->decimal('customer_transaction_fee_value')->after('customer_transaction_fee_type')->nullable();
            $table->decimal('customer_transaction_fee_amount')->after('customer_transaction_fee_value')->nullable();

            $table->enum('order_commission_type', ['Percentage', 'Fixed'])->after('customer_transaction_fee_amount')->nullable();
            $table->decimal('order_commission_value')->after('order_commission_type')->nullable();
            $table->decimal('order_commission_amount')->after('order_commission_value')->nullable();
            $table->decimal('vendor_received_amount')->after('order_commission_amount')->nullable();
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
            $table->string("commission_type",20)->after("discounted_amount")->nullable();
            $table->string("commission_value",20)->after("commission_type")->nullable();
            $table->dropColumn(['security_option', 'security_option_value','security_option_type', 'security_option_amount', 'customer_transaction_fee_type', 'customer_transaction_fee_value', 'customer_transaction_fee_amount', 'order_commission_type', 'order_commission_value', 'order_commission_amount', 'vendor_received_amount']);
        });
    }
}
