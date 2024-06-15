<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->text('specification')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('category_id')->constrained('categories');
            $table->integer('quantity')->default(1);
            $table->float('rent', 8, 2);
            $table->float('price', 8, 2)->nullable();
            $table->float('security', 8, 2);
            $table->enum('status', ['0', '1'])->default('1');
            $table->foreignId('modified_by')->nullable()->constrained('users');
            $table->enum('modified_user_type', ['Self', 'Admin'])->default('Self');
            $table->integer('available')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
