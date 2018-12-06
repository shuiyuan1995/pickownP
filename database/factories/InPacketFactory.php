<?php

use Faker\Generator as Faker;

$factory->define(App\Models\InPacket::class, function (Faker $faker) {
    return [
        'outid' => \App\Models\OutPacket::inRandomOrder()->first()->id,
        'userid' => \App\Models\User::inRandomOrder()->first()->id,
        'income_sum' => $faker->randomFloat(),
        'is_chailei' => random_int(1, 2),
        'blocknumber' => str_random(),
        'eosid' => str_random(),
        'is_reward' => random_int(1, 2),
        'reward_type' => random_int(0, 6),
        'reward_sum' => $faker->randomFloat(),
        'addr' => str_random(),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime
    ];
});
