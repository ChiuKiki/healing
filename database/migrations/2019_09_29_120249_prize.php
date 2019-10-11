<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Prize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prize', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('prize_id');            //奖品编号
            $table->string('prize_name');           //奖品名称
            $table->integer('prize_left');          //奖品剩余量
            $table->text('content')->nullable();    //奖品说明
            $table->float('probability')->default(0);   //概率
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
        Schema::dropIfExists('prize');
    }
}
