<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('vin');

            $table->integer('miles')->nullable();

            $table->integer('year')->nullable();
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->string('trim')->nullable();
            $table->string('body')->nullable();
            $table->string('drivetrain')->nullable();

            $table->string('transmission')->nullable();
            $table->integer('city_mpg')->nullable();
            $table->integer('hwy_mpg')->nullable();

            $table->integer('front_tire_pressure')->nullable();
            $table->integer('rear_tire_pressure')->nullable();

            $table->integer('right_wiper_length')->nullable();
            $table->integer('left_wiper_length')->nullable();

            $table->string('engine_oil_type')->nullable();
            $table->string('transmission_fluid_type')->nullable();

            $table->string('ext_color')->nullable();
            $table->integer('gas_tank_percentage')->nullable();

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
        Schema::dropIfExists('vehicles');
    }
}
