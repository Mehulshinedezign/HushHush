<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBillingTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('billing_tokens', function (Blueprint $table) {
            $table->enum('security_option', ['security', 'insurance'])->after('rent')->nullable();
            $table->enum('security_option_type', ['Percentage', 'Fixed'])->after('security_option')->nullable();
            $table->float('security_option_value')->after('security_option_type')->nullable();
            $table->float('security_option_amount')->after('security_option_value')->nullable();

            $table->enum('customer_transaction_fee_type', ['Percentage', 'Fixed'])->after('security_option_amount')->nullable();
            $table->float('customer_transaction_fee_value')->after('customer_transaction_fee_type')->nullable();
            $table->float('customer_transaction_fee_amount')->after('customer_transaction_fee_value')->nullable();
            $table->string('pickup_datetime', 50)->after('to_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('billing_tokens', function (Blueprint $table) {
            $table->dropColumn(['security_option', 'security_option_value', 'security_option_type', 'security_option_amount', 'customer_transaction_fee_type', 'customer_transaction_fee_value', 'customer_transaction_fee_amount']);
        });
    }
}
