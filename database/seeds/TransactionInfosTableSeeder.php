<?php

use Illuminate\Database\Seeder;

class TransactionInfosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\TransactionInfo::class, 20)->create()->each(function () {
            factory(\App\Models\TransactionInfo::class)->make();
        });
    }
}
