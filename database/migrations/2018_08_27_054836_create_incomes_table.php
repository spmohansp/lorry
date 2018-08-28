<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('date');
            $table->integer('clientId')->unsigned();
            $table->foreign('clientId')->references('id')->on('clients');
            $table->integer('vehicleId')->unsigned();
            $table->foreign('vehicleId')->references('id')->on('vehicles');
            $table->string('receivingAmount')->nullable();
            $table->string('discountAmount')->nullable();
            $table->integer('userId')->unsigned();
            $table->foreign('userId')->references('id')->on('users');
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
        Schema::dropIfExists('incomes');
    }
}
