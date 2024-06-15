<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->string('chatid')->nullable();
            // $table->text('message')->nullable();
            // $table->string('file')->nullable();
            // $table->string('url')->nullable();
            $table->foreignId('order_id')->constrained('orders');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('retailer_id')->nullable()->constrained('users');
            $table->enum('sent_by', ['Admin', 'Customer', 'Retailer']);
            $table->timestamp('last_msg_datetime')->nullable();
            $table->text('last_msg')->nullable();
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
        Schema::dropIfExists('chats');
    }
}
