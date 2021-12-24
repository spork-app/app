<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStoreItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('product_id');
            $table->string('store');
            $table->boolean('is_in_stock')->default(false);
            $table->boolean('is_available_in_local_store')->default(false);
            $table->float('price', 6, 2)->default(0.0);
            $table->string('unit')->nullable();
            $table->json('ingredients')->nullable();
            $table->string('image_url')->nullable();
            $table->boolean('is_alcohol')->default(false);
            $table->boolean('is_home_delivery_available')->default(false);
            $table->string('manufacturer')->nullable();
            $table->string('category')->nullable();
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
        Schema::drop('store_items');
    }
}
