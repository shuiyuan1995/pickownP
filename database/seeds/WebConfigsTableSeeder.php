<?php

use Illuminate\Database\Seeder;

class WebConfigsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $web_config = [
            ['key'=>'contract','name'=>'合约账户','content'=>''],
            ['key'=>'revenue','name'=>'营收账户','content'=>''],
            ['key'=>'reward','name'=>'奖励账户','content'=>''],
            ['key'=>'mining','name'=>'挖矿账户','content'=>''],
            ['key'=>'airdrop','name'=>'空投账户','content'=>''],
            ['key'=>'fenhong','name'=>'分红账户','content'=>''],
        ];
        DB::table('web_config')->delete();
        DB::table('web_config')->insert($web_config);
    }
}
