<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('date');
            $table->string('expenseType');
            $table->integer('vehicleId')->unsigned()->nullable();
            $table->foreign('vehicleId')->references('id')->on('vehicles');
            $table->string('quantity')->nullable();
            $table->integer('staffId')->unsigned()->nullable();
            $table->foreign('staffId')->references('id')->on('staff');
            $table->string('amount');
            $table->string('discription')->nullable();
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
        Schema::dropIfExists('expenses');
    }
}
