<?php

use Illuminate\Database\Seeder;

class InPacketsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\InPacket::class, 20)->create()->each(function($u) {
            factory(App\Models\InPacket::class)->make();
        });
    }
}
