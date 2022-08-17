<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMonthlyBalanceSheets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_balance_sheets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('year');
            $table->unsignedTinyInteger('month');
            $table->unsignedInteger('type_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('target_id');
            $table->unsignedInteger('amount');
            $table->unsignedDecimal('unit_price', 14, 2);
            $table->unsignedDecimal('price', 14, 2);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('type_id')->references('id')->on('balance_types')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('target_id')->references('id')->on('targets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monthly_balance_sheets');
    }
}
