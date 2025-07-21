<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EventVirrunTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_virrun', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ebib');
            $table->time('mvtime');
            $table->decimal('distance');
            $table->text('url')->nullable();
            $table->boolean('verify')->default(false)->nullable();
            $table->boolean('delete')->default(false)->nullable();
            $table->bigInteger('user_verify')->nullable();
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
        Schema::dropIfExists('event_virrun');
    }
}
