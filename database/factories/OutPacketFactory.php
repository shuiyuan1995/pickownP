<?php

use Faker\Generator as Faker;

$factory->define(App\Models\OutPacket::class, function (Faker $faker) {
    return [
        'gameid'=>\App\Models\GamePartition::inRandomOrder()->first()->id,
        'userid'=>\App\Models\User::inRandomOrder()->first()->id,
        'seed_sum'=>random_int(10000,800000000),
        'number'=>random_int(1,10),
        'surplus_sum'=>random_int(10000,800000000),
        'count'=> random_int(1,10),
        'up'=>random_int(500,3000),
        'down'=>random_int(500,3000),
        'surplus_count'=>random_int(1,10),
        'status'=>random_int(1,4),
        'created_at'=>$faker->dateTime,
        'updated_at'=>$faker->dateTime
    ];
});
