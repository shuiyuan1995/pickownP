<?php

use Faker\Generator as Faker;

$factory->define(App\Models\User::class, function (Faker $faker) {
    return [
        'name'=>$faker->name,
        'password'=>'123456',
        'walletid'=>str_random(16),
        'last_time'=>$faker->dateTime,
        'status'=> random_int(1,2),
        'created_at'=>$faker->dateTime,
        'updated_at'=>$faker->dateTime
    ];
});
