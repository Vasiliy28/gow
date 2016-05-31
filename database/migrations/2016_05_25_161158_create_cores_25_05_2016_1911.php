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
            $table->integer('id')->unique();
            $table->string('title', 255);
            $table->text('images');
            $table->string('event', 255);
            $table->string('slot', 255);
            $table->text('boostname');
            $table->text('levels');
            $table->timestamps();
            $table->primary(['id']);
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
