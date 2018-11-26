<?php

use Illuminate\Database\Seeder;

class GamePartitionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\GamePartition::class, 3)->create()->each(function($u) {
            factory(App\Models\GamePartition::class)->make();
        });
    }
}
