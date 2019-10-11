<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Laud extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * This is the table for laud
         */
        Schema::create('laud', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->comment('can be heal, story, overt or seasnail');
            $table->integer('target_id');
            $table->integer('user_id')->comment('the user id of the creator');
            $table->index('target_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
