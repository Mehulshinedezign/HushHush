<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFromTimeToTimeColumnInBillingTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('billing_tokens', function (Blueprint $table) {
            $table->string('from_hour')->after('to_date')->nullable();
            $table->string('from_minute')->after('from_hour')->nullable();
            $table->string('to_hour')->after('from_minute')->nullable();
            $table->string('to_minute')->after('to_hour')->nullable();
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
            $table->dropColumn('from_hour');
            $table->dropColumn('from_minute');
            $table->dropColumn('to_hour');
            $table->dropColumn('to_minute');
        });
    }
}
