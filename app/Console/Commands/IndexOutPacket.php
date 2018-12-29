<?php

namespace App\Console\Commands;

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class IndexOutPacket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'index:out_packet';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '在链上查询存在的红包';

    /**
     * Create a new command instance.
     *
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
        $url = 'http://35.197.130.214/pickown/packet_data/get_packet_data.php';
        $data = request_curl($url, [], false, false);
        Log::info('查询链上红包存在的信息：' . $data);

        return true;
    }
}
