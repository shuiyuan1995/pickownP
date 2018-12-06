<?php

use Faker\Generator as Faker;

$factory->define(App\Models\OutPacket::class, function (Faker $faker) {
    return [
        'userid' => \App\Models\User::inRandomOrder()->first()->id,
        'issus_sum' => $faker->randomElement([1, 5, 10, 20, 50, 100]),
        'count' => 10,
        'eosid' => str_random(),
        'blocknumber' => str_random(),
        'tail_number' => random_int(0, 9),
        'status' => random_int(1, 2),
        'addr' => str_random(),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime
    ];
});
