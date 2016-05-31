<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGems181030052016 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gems' , function (Blueprint $table){
            $table->integer('id')->unique();
            $table->string('title', 255);
            $table->text('images');
            $table->string('event', 255);
            $table->integer('four_th_slot');
            $table->text('boostname');
            $table->text('levels');
            $table->text('gallery');
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
        Schema::drop('gems');
    }
}
