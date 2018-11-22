<?php

use Faker\Generator as Faker;

$factory->define(App\Models\GamePartition::class, function (Faker $faker) {
    return [
        'name'=> $faker->name,
        'sum' => random_int(10000,500000),
        'up'=> 3000,
        'down'=> 500,
        'number'=> random_int(1,9),
        'status'=> random_int(1,2),
        'created_at'=> $faker->dateTime,
        'updated_at'=> $faker->dateTime
    ];
});
