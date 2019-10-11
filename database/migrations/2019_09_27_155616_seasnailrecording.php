<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Seasnailrecording extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * This is the table of recordings for seasnail module
         */
        Schema::create('seasnailrecording', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('the user id of singer');
            $table->integer('seasnail_id')->comment('the id of seasnail: its parent');
            $table->text('url')->comment('the url of the recording in qiniu');
            $table->timestamps();
            $table->index('user_id');
            $table->index('seasnail_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seasnailrecording');
    }
}
