<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Overtrecording extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * This is the table of recordings for public recording
         */
        Schema::create('overtrecording', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('the user id of singer');
            $table->string('name')->comment('name of the song');
            $table->string('lang')->nullable();
            $table->text('url')->comment('the url of the recording in qiniu');
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
        Schema::dropIfExists('overtrecording');
    }
}
