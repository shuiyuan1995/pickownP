<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\UserBehaviorLog::class, function (Faker $faker) {
    return [
        'userid'=>\App\Models\User::inRandomOrder()->first()->id,
        'type'=>random_int(1,6),
        'msg'=>$faker->text(1024),
        'created_at'=>$faker->dateTime,
        'updated_at'=>$faker->dateTime
    ];
});
