<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Reward::class, function (Faker $faker) {
    return [
        'userid' => \App\Models\User::inRandomOrder()->first()->id,
        'pairs' => $faker->randomFloat(),
        'three' => $faker->randomFloat(),
        'min' => $faker->randomFloat(),
        'int' => $faker->randomFloat(),
        'shunzi' => $faker->randomFloat(),
        'bomb' => $faker->randomFloat(),
        'max' => $faker->randomFloat(),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
    ];
});
