<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBalanceTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balance_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 10);
        });

        $this->fillBalanceTypes();
    }

    private function fillBalanceTypes()
    {
        $data = [
            ['name' => 'Debit'],
            ['name' => 'Credit'],
        ];

        DB::table('balance_types')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('balance_types');
    }
}
