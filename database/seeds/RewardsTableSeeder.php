<?php

use Illuminate\Database\Seeder;

class RewardsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Reward::class, 20)->create()->each(function () {
            factory(\App\Models\Reward::class)->make();
        });
    }
}
