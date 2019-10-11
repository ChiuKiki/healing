<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Message;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Message::class, function (Faker $faker) {
    return [
        'user1' => mt_rand(1,50),        
        'user2' => mt_rand(51,100),
        'from' => mt_rand(1,100),       
        'time' => now(),       
        'content' => Str::random(10),
        'type' => mt_rand(1,5),
        'isread' => mt_rand(0,1),
        'last' => mt_rand(0,1),
    ];
});
