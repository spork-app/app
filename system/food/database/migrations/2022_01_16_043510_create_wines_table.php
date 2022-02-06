<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wines', function (Blueprint $table) {
            $table->string('id')->unique();
            $table->string('displayName');
            $table->string('name');
            $table->string('brand')->nullable();
            $table->string('tasting_note', 2048)->nullable();
            $table->string('classification')->nullable();
            $table->string('type')->nullable();
            $table->string('country')->nullable();
            $table->text('grape')->nullable();
            $table->string('region')->nullable();
            $table->text('pairings')->nullable();
            $table->string('imageLink', 2048)->nullable();
            $table->timestamps();
        });
        Schema::create('attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('value')->nullable();
            $table->timestamps();
        });

        Schema::create('wine_attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('attribute_id')->unsigned();
            $table->string('wine_id');

            $table->unique(['attribute_id', 'wine_id']);

            $table->timestamps();
        });
        Schema::create('wine_flavors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
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
        Schema::dropIfExists('wines');
        Schema::dropIfExists('wine_attributes');
        Schema::dropIfExists('attributes');
        Schema::dropIfExists('wine_flavors');

    }
}
