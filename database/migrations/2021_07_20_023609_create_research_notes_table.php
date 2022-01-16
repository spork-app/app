<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResearchNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('research_notes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->nullable();
            $table->longText('body')->nullable();
            $table->string('url', 2048)->nullable();
            $table->foreignIdFor(\App\Models\FeatureList::class);
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
        Schema::dropIfExists('research_notes');
    }
}
