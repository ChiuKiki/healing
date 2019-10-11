<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class User extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('nickname');
            $table->string('realname')->nullable();
            $table->integer('sex');
            $table->mediumText('avatar');
            $table->string('avatar_hash');
            $table->string('openid', 63);
            $table->string('phone', 15)->nullable();
            $table->string('school')->nullable();
            $table->integer('phonesearch')->default(0);
            $table->integer('realnamesearch')->default(0);
            $table->integer('avatar_visible')->default(1);
            $table->integer('upload_to_overt')->default(1);
            $table->mediumText('signature')->nullable();
            $table->timestamps();
            $table->unique('openid');
            $table->index('phone');
            $table->index('realname');
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
        Schema::dropIfExists('user');
    }
}
