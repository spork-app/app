<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('steps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('recipe_id');
            $table->integer('index');
            $table->text('instructions');
            $table->text('instructionsMarkdown');
            $table->text('timers')->nullable();
            $table->text('images')->nullable();
            $table->text('videos')->nullable();
            $table->timestamps();
        });

        Schema::create('step_utensils', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('step_id')->unsigned();
            $table->string('utensil_id');

            $table->unique(['step_id', 'utensil_id']);

            $table->timestamps();
        });
        Schema::create('step_ingredients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('step_id')->unsigned();
            $table->string('ingredient_id');

            $table->unique(['step_id', 'ingredient_id']);

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
        Schema::dropIfExists('steps');
        Schema::dropIfExists('step_utensils');
        Schema::dropIfExists('step_ingredients');
    }
}
