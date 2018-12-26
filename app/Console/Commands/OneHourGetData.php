<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;

class OneHourGetData extends Command
{
    /**
     * 获取一小时前的数据
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
    protected $description = '获取一小时前的数据';

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
        $time = time();
        $eosparket_key = config('app.eospark_key');
        $url = "https://api.eospark.com/api?module=account&action=get_account_related_trx_info&apikey={$eosparket_key}&account=pickowngames&page=1&size=1&symbol=EOS";
        $data = request_curl($url, [], false, true);
        $dataArr = json_decode($data, true);
        if (!isset($dataArr['errno'])) {
            Log::error('返回数据错误，数据为：'.$data);
            return '';
        }
        if (!isset($dataArr['data'])){
            Log::error('data字段不存在，数据为：'.$data);
        }
        if (!isset($dataArr['data']['trace_count'])){
            Log::error('trace_count字段不存在，数据为：'.$data);
        }
        // 获取总的条数
        $trace_count = $dataArr['data']['trace_count'];
        // 单页条数 20
        $page_count = 20;
        $count = intval($trace_count / $page_count);
        for ($i = 1; $i <= $count; $i++) {
            $page = $i;
            $url = "https://api.eospark.com/api?module=account&action=get_account_related_trx_info&apikey={$eosparket_key}&account=pickowngames&page={$page}&size={$page_count}&symbol=EOS";
            $data = request_curl($url, [], false, true);
            $dataArr = json_decode($data, true);
            if (!isset($dataArr['errno'])) {
                Log::error('返回数据错误，数据为：'.$data);
                return '';
            }
            if (!isset($dataArr['data'])){
                Log::error('data字段不存在，数据为：'.$data);
            }
            if (!isset($dataArr['data']['trace_count'])){
                Log::error('trace_count字段不存在，数据为：'.$data);
            }
            if (!isset($dataArr['data']['trace_list'])){
                Log::error('trace_list字段不存在，数据为：'.$data);
            }
            foreach ($dataArr['data']['trace_list'] as $item => $value){
//                echo json_encode($value)."\n";
                echo $value['trx_id']."\n";
                echo $value['timestamp']."\n";
                $ttime = strtotime($value['timestamp']);
                // 转换时区后的时间戳
                $tr_time = $ttime + (8 * 60 * 60);
                if ($tr_time < ($time - (60 * 60 * 24))){
                    break 2;
                }
                echo '转换时区后的时间：'.date('Y-m-d H:i:s',$tr_time)."\n";
                echo $value['receiver']."\n";
                echo $value['memo']."\n";
                $memo_arr = json_decode($value['memo'],true);
                if (json_last_error() == JSON_ERROR_SYNTAX) {
                    echo '编码错误' . "\n";
//                    continue;
                }

                if (isset($memo_arr['packet_id'])){
                    echo $memo_arr['packet_id']."\n";
                }elseif (isset($memo_arr['TYPE'])){
                    echo $memo_arr['TYPE']."\n";
                }

            }
            echo "第{$i}次请求\n";
            //dump($dataArr);
            $start = time();
            Log::info("第{$i}次请求，请求开始时间：" . date('Y-m-d H:i:s', $start));
            Log::info($dataArr);
            $end = time();
            Log::info("第{$i}次请求，请求结束时间：". date('Y-m-d H:i:s', $end));
            Log::info("第{$i}次请求，时间长度" . ($end - $start));
        }
    }
}
