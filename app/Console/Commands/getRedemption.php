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
            echo "获取赎回信息成功\n";
        }
        if ($this->execRedemption()) {
            echo "执行赎回动作成功\n";
        }
        return true;
    }

    /**
     * 获取赎回的信息
     * @return true
     */
    public function getTableRows()
    {
        $url = config('app.eos_interface_addr') . ':8888';
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
            echo "request_id:{$row['request_id']}\n";
            echo "{$row['username']}\n";
            echo "balown:" . ($row['balown'] / 10000) . "\n";
            $request_time = (int)substr($row['request_time'], 0, -6);
//            echo $row['request_time'] . '转换后的时间：' .
//                date('Y-m-d H:i:s', $request_time) . "\n";
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
                ->where('request_time', $request_time)
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
        if (config('app.env') == 'production'){
            $contractabi = 'mcontractabi.php';
        }else{
            $contractabi = 'contractabi.php';
        }
        $url = config('app.eos_interface_addr') . '/eosapi/'.$contractabi;
        $redemption_list = Redemption::query()
            ->where('status', 1)
            ->get();
        foreach ($redemption_list as $entity) {
            $request_time = $entity->request_time;
            echo $request_time . "\n";
            if ((time() - (60 * 60 * 0)) >= $request_time) {
                $id = $entity->id;
                $request_id = $entity->request_id;
                $username = $entity->userid;
                $paramArr = [
                    "contractName" => "pickownbonus",
                    "action" => "ownsend",
                    "params" => [$entity->request_id]
                ];
                $info = request_curl($url, $paramArr, true, false);
                $info = trim(trim($info, '<br>'));
                $msg = <<<EOF
array:2 [
  "transaction_id" => "521a0b62f069336fb9e11c0046a1cd31a65de77f6b31ffe9c3d3db0359dcd7ef"
  "processed" => array:10 [
    "id" => "521a0b62f069336fb9e11c0046a1cd31a65de77f6b31ffe9c3d3db0359dcd7ef"
    "block_num" => 532857
    "block_time" => "2019-01-13T14:32:30.000"
    "producer_block_id" => null
    "receipt" => array:3 [
      "status" => "executed"
      "cpu_usage_us" => 660
      "net_usage_words" => 13
    ]
    "elapsed" => 660
    "net_usage" => 104
    "scheduled" => false
    "action_traces" => array:1 [
      0 => array:12 [
        "receipt" => array:7 [
          "receiver" => "pickownbonus"
          "act_digest" => "aa67c95923ea01d93983a4c265fd76b8ca525d0debb6a46490b7670d76a58563"
          "global_sequence" => 533404
          "recv_sequence" => 67
          "auth_sequence" => array:1 [
            0 => array:2 [
              0 => "tester"
              1 => 153
            ]
          ]
          "code_sequence" => 9
          "abi_sequence" => 7
        ]
        "act" => array:5 [
          "account" => "pickownbonus"
          "name" => "ownsend"
          "authorization" => array:1 [
            0 => array:2 [
              "actor" => "tester"
              "permission" => "active"
            ]
          ]
          "data" => array:1 [
            "id" => 0
          ]
          "hex_data" => "0000000000000000"
        ]
        "context_free" => false
        "elapsed" => 209
        "console" => ""
        "trx_id" => "521a0b62f069336fb9e11c0046a1cd31a65de77f6b31ffe9c3d3db0359dcd7ef"
        "block_num" => 532857
        "block_time" => "2019-01-13T14:32:30.000"
        "producer_block_id" => null
        "account_ram_deltas" => array:1 [
          0 => array:2 [
            "account" => "pickownbonus"
            "delta" => -256
          ]
        ]
        "except" => null
        "inline_traces" => array:1 [
          0 => array:12 [
            "receipt" => array:7 [
              "receiver" => "pickowntoken"
              "act_digest" => "ba4b990e298a75e64144fb114b3b8d2b6bcdcac08b94264c00e3850a48cd994e"
              "global_sequence" => 533405
              "recv_sequence" => 28
              "auth_sequence" => array:1 [
                0 => array:2 [
                  0 => "pickownbonus"
                  1 => 51
                ]
              ]
              "code_sequence" => 1
              "abi_sequence" => 1
            ]
            "act" => array:5 [
              "account" => "pickowntoken"
              "name" => "transfer"
              "authorization" => array:1 [
                0 => array:2 [
                  "actor" => "pickownbonus"
                  "permission" => "active"
                ]
              ]
              "data" => array:4 [
                "from" => "pickownbonus"
                "to" => "shuiyuan2345"
                "quantity" => "33.0000 OWN"
                "memo" => "OWN withdrawal."
              ]
              "hex_data" => "80f5a467720a91ab50c810d368ef74c31009050000000000044f574e000000000f4f574e207769746864726177616c2e"
            ]
            "context_free" => false
            "elapsed" => 113
            "console" => ""
            "trx_id" => "521a0b62f069336fb9e11c0046a1cd31a65de77f6b31ffe9c3d3db0359dcd7ef"
            "block_num" => 532857
            "block_time" => "2019-01-13T14:32:30.000"
            "producer_block_id" => null
            "account_ram_deltas" => []
            "except" => null
            "inline_traces" => array:2 [
              0 => array:12 [
                "receipt" => array:7 [
                  "receiver" => "pickownbonus"
                  "act_digest" => "ba4b990e298a75e64144fb114b3b8d2b6bcdcac08b94264c00e3850a48cd994e"
                  "global_sequence" => 533406
                  "recv_sequence" => 68
                  "auth_sequence" => array:1 [
                    0 => array:2 [
                      0 => "pickownbonus"
                      1 => 52
                    ]
                  ]
                  "code_sequence" => 1
                  "abi_sequence" => 1
                ]
                "act" => array:5 [
                  "account" => "pickowntoken"
                  "name" => "transfer"
                  "authorization" => array:1 [
                    0 => array:2 [
                      "actor" => "pickownbonus"
                      "permission" => "active"
                    ]
                  ]
                  "data" => array:4 [
                    "from" => "pickownbonus"
                    "to" => "shuiyuan2345"
                    "quantity" => "33.0000 OWN"
                    "memo" => "OWN withdrawal."
                  ]
                  "hex_data" => "80f5a467720a91ab50c810d368ef74c31009050000000000044f574e000000000f4f574e207769746864726177616c2e"
                ]
                "context_free" => false
                "elapsed" => 64
                "console" => ""
                "trx_id" => "521a0b62f069336fb9e11c0046a1cd31a65de77f6b31ffe9c3d3db0359dcd7ef"
                "block_num" => 532857
                "block_time" => "2019-01-13T14:32:30.000"
                "producer_block_id" => null
                "account_ram_deltas" => []
                "except" => null
                "inline_traces" => []
              ]
              1 => array:12 [
                "receipt" => array:7 [
                  "receiver" => "shuiyuan2345"
                  "act_digest" => "ba4b990e298a75e64144fb114b3b8d2b6bcdcac08b94264c00e3850a48cd994e"
                  "global_sequence" => 533407
                  "recv_sequence" => 15
                  "auth_sequence" => array:1 [
                    0 => array:2 [
                      0 => "pickownbonus"
                      1 => 53
                    ]
                  ]
                  "code_sequence" => 1
                  "abi_sequence" => 1
                ]
                "act" => array:5 [
                  "account" => "pickowntoken"
                  "name" => "transfer"
                  "authorization" => array:1 [
                    0 => array:2 [
                      "actor" => "pickownbonus"
                      "permission" => "active"
                    ]
                  ]
                  "data" => array:4 [
                    "from" => "pickownbonus"
                    "to" => "shuiyuan2345"
                    "quantity" => "33.0000 OWN"
                    "memo" => "OWN withdrawal."
                  ]
                  "hex_data" => "80f5a467720a91ab50c810d368ef74c31009050000000000044f574e000000000f4f574e207769746864726177616c2e"
                ]
                "context_free" => false
                "elapsed" => 4
                "console" => ""
                "trx_id" => "521a0b62f069336fb9e11c0046a1cd31a65de77f6b31ffe9c3d3db0359dcd7ef"
                "block_num" => 532857
                "block_time" => "2019-01-13T14:32:30.000"
                "producer_block_id" => null
                "account_ram_deltas" => []
                "except" => null
                "inline_traces" => []
              ]
            ]
          ]
        ]
      ]
    ]
    "except" => null
  ]
]

EOF;

                dump(json_decode($info, true));
                $update = Redemption::find($id);
                $update->status = 2;
                $update->save();
            }
        }
        return true;
    }
}
