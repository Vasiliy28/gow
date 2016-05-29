<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePieces2140262015 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pieces', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->text('images');
            $table->string('event', 255);
            $table->text('boostname');
            $table->text('levels');
            $table->timestamps();
            $table->integer('piece_id')->unique();
        });
        
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pieces');
    }
}
