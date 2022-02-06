<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();

            // Something that can have work done on it.
            $table->nullableMorphs('workable');

            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('due_at')->nullable();

            $table->string('priority');
            $table->foreignIdFor(App\Models\User::class, 'assignee_id')->nullable();

            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();

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
        Schema::dropIfExists('work_orders');
    }
}
