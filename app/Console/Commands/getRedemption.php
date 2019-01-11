<?php

namespace App\Console\Commands;

use App\Models\Redemption;
use App\Models\User;
use Illuminate\Console\Command;

class getRedemption extends Command
{
    /**
     * 获取赎回的信息
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:redemption';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取赎回信息';

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
        if ($this->getTableRows()) {
            echo "获取赎回信息成功了耶！\n";
        }
        if ($this->execRedemption()) {
            echo "执行赎回动作成功了耶！\n";
        }
        return true;
    }

    /**
     * 获取赎回的信息
     * @return true
     */
    public function getTableRows()
    {
//        $url = 'http://119.28.88.222:8888';//正式的url
        $url = 'http://35.197.130.214:8888';//测试的url
        $scope = 'pickownbonus';
        $code = 'pickownbonus';
        $table = 'wdowntab';//赎回表表名
        $limit = null;
        $info = get_table_rows($url, $scope, $code, $table, $limit, null);

        // 返回上的数据样例
//        $info = <<<EOF
//{
//  "rows": [{
//      "request_id": 0,
//      "username": "shuiyuan2345",
//      "balown": 10000,
//      "request_time": "1546696108000000"
//    },{
//      "request_id": 1,
//      "username": "shuiyuan2345",
//      "balown": 10000,
//      "request_time": "1546696146000000"
//    },{
//      "request_id": 2,
//      "username": "shuiyuan2345",
//      "balown": 10000,
//      "request_time": "1546696457500000"
//    }
//  ],
//  "more": false
//}
//EOF;
        if ($info === false) {
            return false;
        }
        $info_array = json_decode($info, true);
        if (!isset($info_array['rows'])) {
            echo "rows不存在\n";
            return false;
        }
        if (!isset($info_array['more'])) {
            echo "more\n";
            return false;
        }
        $rows = $info_array['rows'];
        $more = $info_array['more'];
        foreach ($rows as $row) {
//            dump($row);
            echo "request_id:{$row['request_id']}\n";
            echo "{$row['username']}\n";
            echo "balown:" . ($row['balown'] / 10000) . "\n";
            $request_time = (int)substr($row['request_time'], 0, -6);
            echo $row['request_time'] . '转换后的时间：' .
                date('Y-m-d H:i:s', $request_time) . "\n";
            $userEntity = User::query()->where('name', $row['username'])->first();
            if (empty($userEntity)) {
                $userData = [
                    'name' => $row['username'],
                    'publickey' => '',
                    'walletid' => '',
                    'addr' => '',
                    'invite' => '',
                    'status' => 1
                ];
                $userEntity = User::create($userData);
            }
            $entity = Redemption::query()
                ->where('request_id', $row['request_id'])
                ->first();
            if (empty($entity)) {
                $data = [
                    'request_id' => $row['request_id'],
                    'userid' => $userEntity->id,
                    'sum' => ((int)$row['balown'] / 10000),
                    'coin_type' => 'own',
                    'request_time' => $request_time,
                    'status' => 1
                ];
                Redemption::create($data);
            }
        }
        return true;
    }

    /**
     * 执行赎回的动作
     * @return bool
     */
    public function execRedemption()
    {
        $redemption_list = Redemption::query()
            ->where('status', 1)
            ->get();
        foreach ($redemption_list as $entity) {
            $request_time = $entity->request_time;
            echo $request_time . "\n";
//            if ((time() - (60 * 60 * 24)) >= $request_time) {
//                $request_id = $entity->request_id;
//                $username = $entity->userid;
//            }
        }
        return true;
    }
}
