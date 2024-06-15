<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('customer_id')->constrained('users');
            $table->foreignId('retailer_id')->constrained('users');
            $table->float('rent_per_day', 8, 2)->default(0);
            $table->integer('total_rental_days')->default(1);
            $table->float('security_amount', 8, 2)->default(0);
            $table->string('date', 50);
            $table->float('discounted_amount', 8, 2)->nullable();
            $table->float('tax', 8, 2)->nullable();
            $table->float('taxrate', 8, 2)->nullable();
            $table->float('total', 8, 2)->default(0);
            $table->enum('status', ['Pending', 'Picked Up', 'Completed', 'Cancelled', 'Incomplete', 'Failed', 'Refunded'])->default('Pending');
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
        Schema::dropIfExists('order_items');
    }
}
