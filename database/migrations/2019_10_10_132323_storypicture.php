<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Storypicture extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * This is the table of story module
         */
        Schema::create('storypicture', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('story_id')->comment('the user id of creator');
            $table->string('url');
            $table->timestamps();
            $table->index('story_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('storypicture');
    }
}
