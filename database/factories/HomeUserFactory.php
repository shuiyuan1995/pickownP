<?php

use Faker\Generator as Faker;

$factory->define(App\Models\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'publickey' => str_random(40),
        'walletid' => str_random(16),
        'invite' => str_random(16),
        'addr' => str_random(5),
        'status' => random_int(1, 2),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime
    ];
});
