<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\UserInfo::class, function (Faker $faker) {
    $user = \App\Models\User::inRandomOrder()->first();
    return [
        'userid'=>\App\Models\User::inRandomOrder()->first()->id,
        'email'=>$faker->unique()->safeEmail,
        'phone'=>$faker->phoneNumber,
        'referral_code'=>$user->walletid,
        'invite_id'=>$user->id,
        'in_packet_eos'=>random_int(10000,2000000),
        'out_packet_eos'=>random_int(10000,2000000),
        'reward_eos'=>random_int(10000,2000000),
        'punish_eos'=>random_int(10000,2000000),
        'created_at'=>$faker->dateTime,
        'updated_at'=>$faker->dateTime
    ];
});
