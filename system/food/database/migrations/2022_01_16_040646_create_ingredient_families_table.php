<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIngredientFamiliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('families', function (Blueprint $table) {
            $table->string('id')->unique();
            $table->string('type')->index();
            $table->string('name');
            $table->string('slug');
            $table->string('description')->nullable();
            $table->string('imageLink')->nullable();
            $table->timestamps();
        });

        Schema::create('ingredient_families', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ingredient_id');
            $table->string('family_id');

            $table->unique(['ingredient_id', 'family_id']);

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
        Schema::dropIfExists('ingredient_families');
    }
}
