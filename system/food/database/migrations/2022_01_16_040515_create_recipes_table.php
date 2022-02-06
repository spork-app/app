<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->string('id')->unique();
            $table->string('country')->nullable();
            $table->string('name')->nullable()->index();
            $table->string('seoName')->nullable();
            $table->string('category')->nullable();
            $table->string('slug')->nullable()->index();
            $table->string('headline')->nullable();
            $table->text('description')->nullable();
            $table->text('descriptionMarkdown')->nullable();
            $table->text('seoDescription')->nullable();
            $table->smallInteger('difficulty')->default(1)->index();
            $table->string('prepTime')->nullable()->nullable();
            $table->string('totalTime')->nullable();
            $table->string('servingSize')->nullable();
            $table->string('link', 2048)->nullable();
            $table->string('imageLink', 2048)->nullable();
            $table->string('cardLink', 2048)->nullable();
            $table->string('videoLink', 2048)->nullable();
            $table->string('clonedFrom')->nullable();
            $table->string('canonical')->nullable();
            $table->string('canonicalLink')->nullable();
            $table->longText('yields')->nullable();
            $table->timestamps();
        });
        Schema::create('recipe_wines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('wine_id');
            $table->string('recipe_id');

            $table->unique(['wine_id', 'recipe_id']);

            $table->timestamps();
        });

        Schema::create('recipe_cuisines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cuisine_id');
            $table->string('recipe_id');

            $table->unique(['cuisine_id', 'recipe_id']);

            $table->timestamps();
        });

        Schema::create('recipe_ingredients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ingredient_id');
            $table->string('recipe_id');
            $table->float('amount')->nullable();
            $table->string('unit')->nullable();

            $table->unique(['ingredient_id', 'recipe_id']);

            $table->timestamps();
        });

        Schema::create('recipe_allergens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('allergen_id');
            $table->string('recipe_id');

            $table->unique(['allergen_id', 'recipe_id']);

            $table->timestamps();
        });

        Schema::create('recipe_utensils', function (Blueprint $table) {
            $table->increments('id');
            $table->string('utensil_id');
            $table->string('recipe_id');

            $table->unique(['utensil_id', 'recipe_id']);

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
        Schema::dropIfExists('recipes');
        Schema::dropIfExists('recipe_wines');
        Schema::dropIfExists('recipe_cuisines');
        Schema::dropIfExists('recipe_ingredients');
        Schema::dropIfExists('recipe_allergens');
        Schema::dropIfExists('recipe_utensils');
    }
}
