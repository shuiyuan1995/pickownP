<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OneHourGetData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:one_hour';

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
        $url = 'https://eospark.com/api/account/pickowngames/actions?action_type=token&show_trx_small=1&show_trx_in=1&show_trx_out=1&page=1&size=50';
        dump($data = request_curl($url,[],false,true));
        $dataArr = json_decode($data,true);
        dump($dataArr);
    }
}
