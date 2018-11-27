<?php

use Illuminate\Database\Seeder;

class UserBehaviorLogsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\UserBehaviorLog::class, 20)->create()->each(function () {
            factory(\App\Models\UserBehaviorLog::class)->make();
        });
    }
}
