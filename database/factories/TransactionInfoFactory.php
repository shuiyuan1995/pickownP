<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\TransactionInfo::class, function (Faker $faker) {
    return [
        'issus_userid'=> \App\Models\User::inRandomOrder()->first()->id,
        'income_userid'=>\App\Models\User::inRandomOrder()->first()->id,
        'type'=>random_int(1,2),
        'status'=>random_int(1,4),
        'eos'=>random_int(10000,2000000),
        'issus_count_sum'=>random_int(10000,2000000),
        'msg'=>$faker->text(255),
        'created_at'=>$faker->dateTime,
        'updated_at'=>$faker->dateTime
    ];
});
