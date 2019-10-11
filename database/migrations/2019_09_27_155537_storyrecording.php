<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Storyrecording extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * This is the table of recordings for story module
         */
        Schema::create('storyrecording', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('the user id of singer');
            $table->integer('story_id')->comment('the id of story: its parent');
            $table->text('url')->comment('the url of the recording in qiniu');
            $table->string('name')->comment('the name of the song');
            $table->timestamps();
            $table->index('user_id');
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
        Schema::dropIfExists('storyrecording');
    }
}
