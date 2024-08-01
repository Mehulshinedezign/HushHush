<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id');
            $table->foreignId('user_id')->constrained('users');
            $table->string('payment_method')->nullable();
            $table->float('total', 8, 2)->default(0);
            $table->dateTime('date')->nullable();
            $table->enum('status', ['Completed', 'Paid', 'Failed', 'Incomplete', 'Refunded'])->default('Paid');
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
        Schema::dropIfExists('transactions');
    }
}
