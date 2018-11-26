<?php

use Illuminate\Database\Seeder;

class OutPacketsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\OutPacket::class, 20)->create()->each(function($u) {
            factory(App\Models\OutPacket::class)->make();
        });
    }
}
