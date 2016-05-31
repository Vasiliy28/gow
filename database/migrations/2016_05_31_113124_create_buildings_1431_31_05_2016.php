<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuildings143131052016 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buildings' ,function (Blueprint $table){
            $table->integer('id')->unique();
            $table->primary(['id']);
            $table->string('title');
            $table->text('images');
            $table->string('levels');
            $table->text('woods');
            $table->text('stones');
            $table->text('foods');
            $table->text('ores');
            $table->text('times');
            $table->text('requirements');
            $table->text('masters_hammers');
            $table->text('hero_xp');
            $table->text('power');
            $table->text('bonuses');
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
        Schema::drop('buildings');
    }
}
