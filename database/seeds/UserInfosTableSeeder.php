<?php

use Illuminate\Database\Seeder;

class UserInfosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\UserInfo::class, 20)->create()->each(function($u) {
            factory(App\Models\UserInfo::class)->make();
        });
    }
}
