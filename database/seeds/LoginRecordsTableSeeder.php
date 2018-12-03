<?php

use Illuminate\Database\Seeder;

class LoginRecordsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\LoginRecord::class, 20)->create()->each(function () {
            factory(\App\Models\LoginRecord::class)->create();
        });
    }
}
