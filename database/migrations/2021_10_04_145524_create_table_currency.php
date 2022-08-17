<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCurrency extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->string('flag', 60);
            $table->string('iso_code', 3)->unique();
            $table->string('html_code', 10);
            $table->unsignedTinyInteger('status');
        });

        $this->fillCurrency();
    }

    private function fillCurrency()
    {
        $data = [
            ['name' => 'Euro', 'flag' => 'european-union.svg', 'iso_code' => 'EUR', 'html_code' => '&euro;', 'status' => 1],
            ['name' => 'US dollar', 'flag' => 'united-states.svg', 'iso_code' => 'USD', 'html_code' => '$', 'status' => 1],
            ['name' => 'Romanian LEU', 'flag' => 'romania.svg', 'iso_code' => 'RON', 'html_code' => 'LEI', 'status' => 1],
            ['name' => 'British pound', 'flag' => 'united-kingdom.svg', 'iso_code' => 'GBP', 'html_code' => '&pound;', 'status' => 1],
        ];

        DB::table('currency')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currency');
    }
}