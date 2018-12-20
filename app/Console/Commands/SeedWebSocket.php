<?php

namespace App\Console\Commands;

use App\Events\InPacketEvent;
use App\Http\Resources\InPacketResource;
use App\Models\InPacket;
use App\Models\OutPacket;
use App\Models\TransactionInfo;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Ratchet\Client\WebSocket;
use Ratchet\RFC6455\Messaging\MessageInterface;
use React\EventLoop\Factory;
use React\Socket\Connector;

class SeedWebSocket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:websocket';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '监听网站';

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
        $i = 0;
        $eospark_key = config('app.eospark_key');
        $url = 'wss://ws.eospark.com/v1/ws?apikey=' . $eospark_key;
        $url = 'wss://ws.eospark.com/test/v1/ws?apikey=ac45a8b9a11e4ec1d7a994b76f7c6f17';
        while (true) {
            $loop = Factory::create();
            $reactConnector = new Connector($loop, [
                'dns' => '8.8.8.8',
                'timeout' => 20
            ]);
            $connector = new \Ratchet\Client\Connector($loop, $reactConnector);

            $connector($url, [],
                ['Origin' => 'http://localhost'])
                ->then(function (WebSocket $conn) {
                    $conn->on('message', function (MessageInterface $msg) use ($conn) {
                        // 数据样例1
                        $msgaa = <<<EOP
{
  "errno": 0,
  "msg_type": "data",
  "errmsg": "",
  "data": {
    "trx_id": "f2ed6414c1ddd2c8d96b6d500ca1890d3eee436830607233e75a72600c895737",
    "block_num": 32691027,
    "global_action_seq": 3010998871,
    "trx_timestamp": "2018-12-18T05:52:48.000",
    "actions": [
      {
        "account": "eosio.token",
        "authorization": [
          {
            "actor": "pickowngames",
            "permission": "active"
          }
        ],
        "data": {
          "from": "pickowngames",
          "memo": "{\"packet_id\":\"5\",\"user\":\"shuiyuan2345\",\"own_mined\":\"3000\",\"bomb\":\"0\",\"luck\":\"0\",\"prize_amount\":\"0\",\"is_last\":\"1\",\"new_prize_pool\":\"1187\",\"packet_amount\":\"104\",\"txid\":\"800\",\"refund\":\"1104\"}",
          "quantity": "0.1104 EOS",
          "to": "zhouwanyuwan"
        },
        "hex_data": "8095346c720a91ab300dd77e1aae69fb500400000000000004454f5300000000c2017b227061636b65745f6964223a223736222c2275736572223a227a686f7577616e797577616e222c226f776e5f6d696e6564223a2233303030222c22626f6d62223a2230222c226c75636b223a2230222c227072697a655f616d6f756e74223a2230222c2269735f6c617374223a2230222c226e65775f7072697a655f706f6f6c223a2231313837222c227061636b65745f616d6f756e74223a22313034222c2274786964223a223736303030303030222c22726566756e64223a2231313034227d",
        "name": "transfer"
      }
    ]
  }
}

EOP;
                        // 数据样例2
                        $msgcc = <<<EOP
{"errno":0,"msg_type":"data","errmsg":"","data":{"trx_id":"576ce05a26eadbb57419130a112db9f0094949ffa1c12c89be03892039875025","block_num":32727664,"global_action_seq":3016979934,"trx_timestamp":"2018-12-18T10:59:05.500","actions":[{"account":"eosio.token","authorization":[{"actor":"dengxingchun","permission":"active"}],"data":{"from":"dengxingchun","memo":"select:000079:","quantity":"0.1000 EOS","to":"pickowngames"},"hex_data":"3075436cbacea64a8095346c720a91abe80300000000000004454f53000000000e73656c6563743a3030303037393a","name":"transfer"}]}}
EOP;
                        // 数据样例3
                        $msgbb = <<<EOP
{"errno":0,"msg_type":"data","errmsg":"","data":{"trx_id":"576ce05a26eadbb57419130a112db9f0094949ffa1c12c89be03892039875025","block_num":32727664,"global_action_seq":3016979940,"trx_timestamp":"2018-12-18T10:59:05.500","actions":[{"account":"pickowntoken","authorization":[{"actor":"pickowngames","permission":"active"}],"data":{"from":"pickowngames","memo":"Pickown mining reward.","quantity":"0.3000 OWN","to":"dengxingchun"},"hex_data":"8095346c720a91ab3075436cbacea64ab80b000000000000044f574e00000000165069636b6f776e206d696e696e67207265776172642e","name":"transfer"}]}}
EOP;


                        $data = json_decode($msg, true);
                        if ($data['msg_type'] == 'subscribe_account') {
                            echo "账户订阅成功\n";
                        } elseif ($data['msg_type'] == 'heartbeat') {
                            echo "心跳数据，时间：{$data['data']['heart_beat']}\n";
                            //Log::error($msg);
                        } elseif ($data['msg_type'] == 'data') {
                            echo "抢红包数据\n";
//                        Log::info($msg);
                            $this->manipulationData($data, $msg);
                        }
                    });

                    $conn->on('close', function ($code = null, $reason = null) {
                        echo "Connection closed ({$code} - {$reason})\n";
                        Log::warning('websocket关闭:' . "Connection closed ({$code} - {$reason})\n");
                    });

                    $conn->send('{"msg_type": "subscribe_account","name": "pickowngames"}');
                }, function (\Exception $e) use ($loop) {
                    echo "Could not connect: {$e->getMessage()}\n";
                    Log::error('websocket无法连接:' . "Could not connect: {$e->getMessage()}\n");
                    $loop->stop();
                });
            $loop->run();
            $i++;
            sleep(20);
            echo 'websocket失败的次数。' . $i . "\n";
            Log::warning(date('Y-m-d H:i:s', time()) . 'websocket失败的次数：' . $i . "\n");
            if ($i > 1000000000000) {
                $i = 0;
            }
        }
        return;
    }

    // 数据处理

    /**
     * @param $data
     * @param $msg
     * @return string
     */
    public function manipulationData($data, $msg)
    {
        /**
         * dd_data数据样例
         * array:4 [
         * "from" => "pickowngames"
         * "memo" => "{"packet_id":"76","user":"zhouwanyuwan","own_mined":"3000","bomb":"0","luck":"0","prize_amount":"0","is_last":"0","new_
         * prize_pool":"1187","packet_amount":"104","txid":"76000000","refund":"1104"}"
         * "quantity" => "0.1104 EOS"
         * "to" => "zhouwanyuwan"
         * ]
         */
        $dd_data = $data['data']['actions'][0]['data'];
        // 发送账号
        $from = $dd_data['from'];
        // 接收账号 抢红包账号
        $to = $dd_data['to'];
        // 金额 无用
        $quantity = $dd_data['quantity'];
        // memo信息
        $memo = $dd_data['memo'];

        $memo_arr = json_decode($memo, true);
        if (json_last_error() == JSON_ERROR_SYNTAX) {
            echo '编码错误' . "\n";
            return '';
        }
        if (!isset($memo_arr['user'])) {
            echo '该条不是抢红包记录';
            Log::error('该条不是抢红包记录：' . $msg);
            return '';
        }
        // 发红包id
        $packet_id = $memo_arr['packet_id'];
        $outpacketModel = OutPacket::query()->where('eosid', $packet_id)->first();
        if (empty($outpacketModel)) {
            echo "红包未找到";
            Log::error('红包记录未找到，信息为：' . $msg);
            return '';
        }
        $outid = $outpacketModel->id;
        // 用户名
        $user = $memo_arr['user'];
        $userModel = User::query()->where('name', $user)->first();
        if (empty($userModel)) {
            echo '用户未找到';
            $userData = [
                'name' => $user,
                'publickey' => '',
                'walletid' => '',
                'addr' => '',
                'invite' => '',
                'status' => 1
            ];
            $createUser = User::create($userData);
            $userid = $createUser->id;
            Log::error('用户未找到，信息为：' . $msg);
        } else {
            $userid = $userModel->id;
        }

        // 挖矿
        $own_mined = $memo_arr['own_mined'];
        // 是否踩雷
        $bomb = $memo_arr['bomb'];
        // 奖金种类
        $luck = $memo_arr['luck'];
        // 奖金额
        $prize_amount = $memo_arr['prize_amount'];
        // 是否最后 0 - 不是，1 - 是
        $is_last = $memo_arr['is_last'];
        // 幸运奖池
        $new_prize_pool = $memo_arr['new_prize_pool'];
        // 红包金额
        $packet_amount = $memo_arr['packet_amount'];

        // 抢红包唯一标示
        $txid = $memo_arr['txid'];
        // 退款 - 无用
        $refund = $memo_arr['refund'];
        // 平台利润问题
        $platform_reserve = 0;
        if (isset($memo_arr['platform_reserve'])){
            $platform_reserve = $memo_arr['platform_reserve'] / 10000;
        }

        try {
            \DB::beginTransaction();
            // 检查此条抢红包记录是否存在
            $jiancha_in_packet = InPacket::query()->where('txid', $txid)->first();
            $entity = null;
            if (!empty($jiancha_in_packet)) {
                $jiancha_in_packet->outid = $outid;
                $jiancha_in_packet->userid = $userid;
                $jiancha_in_packet->eosid = $packet_id;
                $jiancha_in_packet->income_sum = $packet_amount / 10000;
                $jiancha_in_packet->is_chailei = !empty($bomb) ? 1 : 2;
                $jiancha_in_packet->is_reward = $luck > 0 ? 2 : 1;
                $jiancha_in_packet->reward_type = $luck;
                $jiancha_in_packet->reward_sum = $prize_amount / 10000;
                $jiancha_in_packet->own = $own_mined / 10000;
                $jiancha_in_packet->prize_pool = $new_prize_pool / 10000;
                $jiancha_in_packet->save();
                $entity = $jiancha_in_packet;
                DB::commit();
                echo '抢红包记录已存在，修改中' . "\n";
            } else {
                $inPacket = [
                    'outid' => $outid,
                    'userid' => $userid,
                    'eosid' => $packet_id,
                    'income_sum' => $packet_amount / 10000,
                    'is_chailei' => !empty($bomb) ? 1 : 2,
                    'is_reward' => $luck > 0 ? 2 : 1,
                    'reward_type' => $luck,
                    'reward_sum' => $prize_amount / 10000,
                    'own' => $own_mined / 10000,
                    'prize_pool' => $new_prize_pool / 10000,
                    'txid' => $txid
                ];
                $entity = InPacket::create($inPacket);
            }
            OutPacket::find($outid)->userid;
            // 抢红包信息
            $data = [
                'issus_userid' => 0,
                'income_userid' => $userid,
                'type' => 1,
                'status' => 1,
                'eos' => $entity->income_sum,
                'addr' => '',
            ];
            TransactionInfo::create($data);

            // 踩雷信息
            if ($bomb > 0) {
                $data['issus_userid'] = 0;
                $data['income_userid'] = $userid;
                $data['type'] = 3;
                $data['eos'] = OutPacket::find($outid)->issus_sum;
                TransactionInfo::create($data);
            }

            // 中奖信息
            if ($luck !== 0) {
                $data['issus_userid'] = 0;
                $data['income_userid'] = $userid;
                $data['type'] = 4;
                $data['eos'] = $prize_amount;
                TransactionInfo::create($data);
            }
            DB::commit();
            echo '抢红包记录创建' . "\n";

            if ($is_last > 0) {
                // 红包被抢完后生成发红包对用的抢红包的列表
                $out_in_packet = InPacket::query()->where('outid', $outid)->get();
                $out_in_packet_sum = InPacket::query()->where('outid', $outid)->sum('income_sum');
                $outPacket_entity = OutPacket::find($outid);
                $outPacket_entity->status = 2;
                $outPacket_entity->surplus_sum = $platform_reserve;
                $outPacket_entity->save();
                $outPacket = $outPacket_entity;
                $out_in_packet_data = array();
                foreach ($out_in_packet as $item => $value) {
                    $out_in_packet_data[$item]['name'] = User::find($value['userid'])->name;
                    $out_in_packet_data[$item]['income_sum'] = $value['income_sum'];
                    $out_in_packet_data[$item]['own'] = $value['own'];
                    $out_in_packet_data[$item]['is_chailei'] = $value['is_chailei'];
                    $out_in_packet_data[$item]['is_reward'] = $value['is_reward'];
                    $out_in_packet_data[$item]['reward_type'] = $value['reward_type'];
                    $out_in_packet_data[$item]['txid'] = $value['txid'];
                    $out_in_packet_data[$item]['reward_sum'] = $value['reward_sum'];
                }

                $name = User::find($outPacket->userid)->name;
                $issus_sum_arr = [
                    0 => -1,
                    '0.1000' => 0.1,
                    '1.0000' => 1,
                    '5.0000' => 5,
                    '10.0000' => 10,
                    '20.0000' => 20,
                    '50.0000' => 50,
                    '100.0000' => 100
                ];
                $index = $issus_sum_arr[$outPacket->issus_sum];
                $outPacket_data['id'] = $outPacket->id;
                $outPacket_data['userid'] = $outPacket->id;
                $outPacket_data['issus_sum'] = $outPacket->issus_sum;
                $outPacket_data['tail_number'] = $outPacket->tail_number;
                $outPacket_data['eosid'] = $outPacket->eosid;
                $outPacket_data['blocknumber'] = $outPacket->blocknumber;
                $outPacket_data['status'] = $outPacket->status;
                $outPacket_data['created_at'] = strtotime($outPacket->created_at);
                $outPacket_data['updated_at'] = strtotime($outPacket->updated_at);
                //if (OutPacket::find($outid)->is_guangbo < 1){
                event(new InPacketEvent(
                    [],
                    $outPacket_data,
                    [],
                    $out_in_packet_data,
                    $name,
                    2,
                    $index,
                    $this->getinfo(),
                    json_decode(json_encode(InPacketResource::make($entity)))
                ));
                $out = OutPacket::find($outid);
                $out->is_guangbo = 1;
                $out->save();
                //}
            } else {
                event(new InPacketEvent(
                    [],
                    [],
                    [],
                    [],
                    [],
                    3,
                    [],
                    $this->getinfo(),
                    json_decode(json_encode(InPacketResource::make($entity)))
                ));
            }

        } catch (\Exception $exception) {
            DB::rollBack();
        }
        return '';
    }

    public function getinfo()
    {
        $outPacketCount = OutPacket::count();
        $outPacketSum = OutPacket::sum('issus_sum');
        $inPacketSum = InPacket::query()->with(['out'])->whereHas('out', function ($q) {
            $q->where('status', 2);
        })->sum('income_sum');
        $diya_sum = DB::select('SELECT sum(issus_sum) AS sum FROM out_packets ,in_packets WHERE in_packets.outid = out_packets.id');
        $ddiya_jsum = 0;
        foreach ($diya_sum as $value) {
            $ddiya_jsum = $value->sum;
        }
        $inPacketCount = InPacket::count();
        $transactionInfoCount = TransactionInfo::where('type', '<', 5)->sum('eos') + $ddiya_jsum;
        $userCount = User::count();
        $xinyujiangchientity = InPacket::orderBy('created_at', 'desc')->first();
        $xinyujiangchi = 0;
        if (!empty($xinyujiangchientity)) {
            $xinyujiangchi = $xinyujiangchientity->prize_pool;
        }
        $data = [
            'out_packet_count' => (string)$outPacketCount,
            'transaction_info_count' => (string)$transactionInfoCount,
            'user_count' => $userCount,
            'out_packet_sum' => (string)$outPacketSum,
            'in_packet_sum' => (string)$inPacketSum,
            'in_packet_count' => $inPacketCount,
            'xinyunjiangchi' => empty($xinyujiangchi) ? 0 : $xinyujiangchi,
        ];
        return $data;
    }
}
