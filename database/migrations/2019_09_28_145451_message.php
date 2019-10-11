<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Message extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
			$table->string('content', 511);
			$table->dateTime('time');
			$table->integer('type')->default(1);
			$table->integer('user1');
			$table->integer('user2');
			$table->integer('from');
			$table->integer('last')->default(0);
			$table->integer('isread')->default(0);
            $table->timestamps();
            $table->index(['user1', 'user2', 'from', 'last']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message');
    }
}
