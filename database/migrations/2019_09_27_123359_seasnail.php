<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Seasnail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * This is the table for seasnails
         */
        Schema::create('seasnail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('the user id of creator');
            $table->string('name', 25)->comment('the name of the song');
            $table->text('note')->comment('a brief note for the song');
            $table->integer('maxrelays')->comment('the maximum number of relaying people');
            $table->timestamps();
            $table->index('user_id');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seasnail');
    }
}
