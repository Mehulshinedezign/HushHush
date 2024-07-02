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
        Schema::table('products', function (Blueprint $table) {
            $table->integer('subcat_id')->nullable()->after('category_id');
            $table->string('size')->nullable()->after('status');
            $table->string('color')->nullable()->after('status');
            $table->string('brand')->nullable()->after('status');
            $table->string('condition')->nullable()->after('status');
            // $table->string('other_size')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
