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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');

            $table->foreignId('product_id')->constrained('products')->onDelete('cascade')->onUpdate('cascade');

            $table->foreignId('for_user')->constrained('users')->onDelete('cascade')->onUpdate('cascade');

            $table->text('query_message')->nullable();
            $table->enum('status', ['ACCEPTED', 'REJECTED', 'PENDING', 'COMPLETED'])->default('PENDING');
            $table->string('date_range');
            $table->timestamps();
            $table->softDeletes();


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
