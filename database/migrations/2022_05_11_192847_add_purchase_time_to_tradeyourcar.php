<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPurchaseTimeToTradeyourcar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trade_your_car', function (Blueprint $table) {
            $table->tinyInteger('purchase_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trade_your_car', function (Blueprint $table) {
            $table->dropColumn('purchase_time');
        });
    }
}
