<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCores250520161911 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->text('images');
            $table->string('event', 255);
            $table->string('slot', 255);
            $table->text('boostname');
            $table->text('levels');
            $table->timestamps();
            $table->integer('core_id')->uniqable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cores');
    }
}
