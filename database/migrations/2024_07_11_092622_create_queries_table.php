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
        Schema::create('queries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // User who owns the product
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('for_user'); // User who the query is for
            $table->text('query_message');
            $table->enum('status',['ACCEPTED','REJECTED','PENDING'])->default('PENDING'); // Example: pending, accepted, declined, etc.
            $table->date('date_range'); // Date range for the query
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('for_user')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queries');
    }
};



