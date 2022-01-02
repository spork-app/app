<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('time_to_harvest');
            $table->string('expected_yield_per_harvest');
            $table->boolean('is_perpetual_harvest')->default(false);
            $table->json('nutrient_schedule')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
        });
        Schema::create('seeds', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Could be random hash
            $table->unsignedBigInteger('plant_id');
            $table->dateTime('germinated_at')->nullable();
            $table->dateTime('planted_at')->nullable();
            $table->dateTime('expected_harvest_date')->nullable();
            $table->dateTime('harvested_at')->nullable();
            $table->dateTime('last_watered_at')->nullable();
            $table->dateTime('last_fed_at')->nullable();
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
        Schema::dropIfExists('seeds');
    }
}
