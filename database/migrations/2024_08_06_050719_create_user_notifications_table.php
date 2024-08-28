<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->enum('query_receive', ['0', '1'])->default('0');
            $table->enum('accept_item',['0','1'])->default('0');
            $table->enum('reject_item',['0','1'])->default('0');
            $table->enum('order_req',['0','1'])->default('0');
            $table->enum('customer_order_req',['0','1'])->default('0');
            $table->enum('customer_order_pickup',['0','1'])->default('0');
            $table->enum('lender_order_pickup',['0','1'])->default('0');
            $table->enum('customer_order_return',['0','1'])->default('0');
            $table->enum('lender_order_return',['0','1'])->default('0');
            $table->enum('order_canceled_by_lender',['0','1'])->default('0');
            $table->enum('order_canceled_by_customer',['0','1'])->default('0');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
    }
};
