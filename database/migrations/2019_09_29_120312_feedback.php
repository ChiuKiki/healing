<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Feedback extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');                 //用户
            $table->text('phone_type')->nullable();     //手机型号
            $table->text('problem')->nullable();        //反馈的bug
            $table->string('pic_url')->nullable();      //bug图片
            $table->string('seasnail_id')->nullable();  //举报的海螺
            $table->text('reason')->nullable();         //举报原因
            $table->timestamps();
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feedback');
    }
}
