<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class getPending extends Command
{
    /**
     * 定时跑的退币脚本
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dataInfo = <<<EOP
{
    "rows":[
        {
            "tx_id":0,
            "user":"eostest1",
            "time":"1546933404000000",
            "packet_id":0,"amount":10000,
            "ref":"pickowngames"
        },
        {
            "tx_id":1,
            "user":"tester",
            "time":"1546933484000000",
            "packet_id":0,"amount":10000,
            "ref":"pickowngames"
        }
    ],
    "more":false
}
EOP;

        $url = 'http://35.197.130.214/eosapi/refundpl.php?user=tester';

        return true;
    }
}
