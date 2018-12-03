<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\LoginRecord::class, function (Faker $faker) {
    return [
        'userid'=>\App\Models\User::inRandomOrder()->first()->id,
        'ip'=>$faker->ipv4,
        'created_at'=>$faker->dateTime,
        'updated_at'=>$faker->dateTime,
    ];
});
