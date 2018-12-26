<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;

class AllGetData extends Command
{
    /**
     * 获取所有的数据
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取所有的数据';

    /**
     * Create a new command instance.
     *
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $eosparket_key = config('app.eospark_key');
        $url = "https://api.eospark.com/api?module=account&action=get_account_related_trx_info&apikey={$eosparket_key}&account=pickowngames&page=1&size=1&symbol=EOS";
        $data = request_curl($url, [], false, true);
        $dataArr = json_decode($data, true);
        if (!isset($dataArr['errno'])) {
            Log::error('返回数据错误');
            return '';
        }
        // 获取总的条数
        $trace_count = $dataArr['data']['trace_count'];
        // 单页条数 20
        $page_count = 20;
        for ($i = 1; $i <= intval($trace_count / $page_count); $i++) {
            $page = $i;
            $url = "https://api.eospark.com/api?module=account&action=get_account_related_trx_info&apikey={$eosparket_key}&account=pickowngames&page={$page}&size={$page_count}&symbol=EOS";
            $data = request_curl($url, [], false, true);
            $dataArr = json_decode($data, true);

            echo "第{$i}次请求\n";
//            dump($dataArr);
            $start = time();
            Log::info("第{$i}次请求，请求开始时间：" . date('Y-m-d H:i:s', $start));
            Log::info($dataArr);
            $end = time();
            Log::info("第{$i}次请求，请求结束时间：". date('Y-m-d H:i:s', $end));
            Log::info("第{$i}次请求，时间长度" . ($end - $start));
        }
        return '';
    }
}
