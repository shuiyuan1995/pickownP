<?php

use Faker\Generator as Faker;

$factory->define(App\Models\InPacket::class, function (Faker $faker) {
    return [
        'outid'=>\App\Models\OutPacket::inRandomOrder()->first()->id,
        'userid'=>\App\Models\User::inRandomOrder()->first()->id,
        'sum'=>random_int(10000,800000000),
        'packet_tail_number'=>random_int(0,9),
        'is_win'=>random_int(1,2),
        'status'=>random_int(1,2),
        'created_at'=>$faker->dateTime,
        'updated_at'=>$faker->dateTime
    ];
});
