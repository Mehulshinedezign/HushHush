<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id()->start_from(10000);
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('query_id')->constrained('queries');
            $table->foreignId('transaction_id')->nullable()->constrained('transactions');
            $table->date('from_date');
            $table->date('to_date');
            $table->dateTime('order_date');
            $table->dateTime('cancelled_date')->nullable();
            $table->string('pickedup_date', 50)->nullable();
            $table->dateTime('returned_date')->nullable();
            $table->enum('status', ['Waiting', 'Picked Up', 'Completed', 'Cancelled', 'Incomplete', 'Failed', 'Refunded'])->default('Waiting');
            $table->enum('customer_confirmed_pickedup', ['0', '1'])->default('0');
            $table->enum('customer_confirmed_returned', ['0', '1'])->default('0');
            $table->enum('retailer_confirmed_pickedup', ['0', '1'])->default('0');
            $table->enum('retailer_confirmed_returned', ['0', '1'])->default('0');
            $table->string('promocode')->nullable();
            $table->enum('discount_type', ['flat', 'percentage'])->nullable();
            $table->float('discount_percentage', 8, 2)->nullable();
            $table->float('discounted_amount', 8, 2)->nullable();
            $table->float('subtotal', 8, 2)->default(0);
            $table->float('tax', 8, 2)->nullable();
            $table->float('taxrate', 8, 2)->nullable();
            $table->float('total', 8, 2)->default(0);
            $table->dateTime('dispute_date')->nullable();
            $table->enum('dispute_status', ['No', 'Yes', 'Resolved'])->default('No');
            $table->text('cancellation_note')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
