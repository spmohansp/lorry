<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dateFrom');
            $table->string('dateTo');
            $table->integer('clientId')->unsigned();
            $table->foreign('clientId')->references('id')->on('clients');
            $table->integer('vehicleId')->unsigned();
            $table->foreign('vehicleId')->references('id')->on('vehicles');
            $table->integer('cleanerId')->unsigned()->nullable();
            $table->foreign('cleanerId')->references('id')->on('staff');
            $table->integer('driverId')->unsigned()->nullable();
            $table->foreign('driverId')->references('id')->on('staff');
            $table->integer('managerId')->unsigned()->nullable();
            $table->foreign('managerId')->references('id')->on('staff');
            $table->string('startKm')->nullable();
            $table->string('endKm')->nullable();
            $table->string('total')->nullable();
            $table->string('locationFrom')->nullable();
            $table->string('locationTo')->nullable();
            $table->string('loadType')->nullable();
            $table->string('ton')->nullable();
            $table->string('billAmount')->nullable();
            $table->string('comission')->nullable();
            $table->string('upLift')->nullable();
            $table->string('downLift')->nullable();
            $table->string('advance')->nullable();
            $table->bigInteger('balance')->nullable();
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
        Schema::dropIfExists('entries');
    }
}
