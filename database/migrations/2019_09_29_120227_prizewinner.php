<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Prizewinner extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prizewinner', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');            //获奖用户
            $table->tinyInteger('prize_id');    //奖品编号
            $table->string('prize_name');       //奖品名称
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
        Schema::dropIfExists('prizewinner');
    }
}
