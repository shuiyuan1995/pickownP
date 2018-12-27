<?php

namespace App\Console\Commands;

use App\Events\InPacketEvent;
use App\Http\Resources\InPacketResource;
use App\Http\Resources\OutPacketResource;
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
     * 此监听脚本处理抢红包记录，监听两种状态。成功或者失败。
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
//        $url = 'wss://ws.eospark.com/v1/ws?apikey=' . $eospark_key;
        $url = 'wss://ws.eospark.com/test/v1/ws?apikey=' . $eospark_key;
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
                        Log::info('源数据：' . $msg);
                        $msgaa = <<<EOP
{
    "errno":0,
    "msg_type":"data",
    "errmsg":"",
    "data":{
        "trx_id":"ac6301ef4534766e184c8dcec9583bba5ca6ed129f04f69fbe6a6249371e6fc6",
        "block_num":33209141,
        "global_action_seq":3120730087,
        "trx_timestamp":"2018-12-21T06:02:59.000",
        "actions":[
            {
                "account":"eosio.token",
                "authorization":[
                    {
                        "actor":"pickowngames",
                        "permission":"active"
                    }
                ],
                "data":{
                    "from":"pickowngames",
                    "memo":"{
                        \"packet_id\":\"344\",
                        \"user\":\"brucewc12345\",
                        \"own_mined\":\"3000\",
                        \"bomb\":\"0\",
                        \"luck\":\"0\",
                        \"prize_amount\":\"0\",
                        \"is_last\":\"0\",
                        \"new_prize_pool\":\"2175\",
                        \"packet_amount\":\"3\",
                        \"txid\":\"344000000\"
                    }",
                    "quantity":"0.1003 EOS",
                    "to":"brucewc12345"
                },
                "hex_data":"8095346c720a91ab50c810017185f43deb0300000000000004454f5300000000b2017b227061636b65745f6964223a22333434222c2275736572223a22627275636577633132333435222c226f776e5f6d696e6564223a2233303030222c22626f6d62223a2230222c226c75636b223a2230222c227072697a655f616d6f756e74223a2230222c2269735f6c617374223a2230222c226e65775f7072697a655f706f6f6c223a2232313735222c227061636b65745f616d6f756e74223a2233222c2274786964223a22333434303030303030227d",
                "name":"transfer"
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
                        // 退款数据样例
                        $msgdd = <<<EOP
{
    "errno":0,
    "msg_type":"data",
    "errmsg":"",
    "data":{
        "trx_id":"6e61082cba758e4b667cee0ae489e2aa6381eb8de48f386f44474a21801146c4",
        "block_num":33534317,
        "global_action_seq":3192968313,
        "trx_timestamp":"2018-12-23T03:20:21.000",
        "actions":[
            {
                "account":"eosio.token",
                "authorization":[
                    {
                        "actor":"pickowngames",
                        "permission":"active"
                    }
                ],
                "data":{
                    "from":"pickowngames",
                    "memo":"{
                        \"TYPE\":\"ERROR_NO_PACKET\",
                        \"packet_id\":\"420\"
                    }",
                    "quantity":"5.0000 EOS",
                    "to":"eoseosboyboy"
                },
                "hex_data":"8095346c720a91abe0e9f1f460aa305550c300000000000004454f53000000002c7b2254595045223a224552524f525f4e4f5f5041434b4554222c227061636b65745f6964223a22343230227d",
                "name":"transfer"
            }
        ]
    }
}
EOP;


                        $data = json_decode($msg, true);
//                        dump($data);
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
        if (!isset($data['data']['trx_id'])) {
            Log::error('trx_id不存在：' . $msg);
            return '';
        }
        if (!isset($data['data']['actions'][0]['data'])) {
            Log::error('data_actions_0_data不存在：' . $msg);
            return '';
        }
        $trxid = $data['data']['trx_id'];
//        dump($trxid);
        $dd_data = $data['data']['actions'][0]['data'];

        if (!isset($dd_data['to'])) {
            Log::error('未解析到用户名：' . $msg);
            return '';
        }
        // 用户名
        $user = $dd_data['to'];
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

        // memo信息

        if (!isset($dd_data['memo'])) {
            Log::error('未解析到memo：' . $msg);
            return '';
        }
        $memo = $dd_data['memo'];
        $memo_arr = json_decode($memo, true);
        if (json_last_error() == JSON_ERROR_SYNTAX) {
            echo '编码错误' . "\n";
            return '';
        }
        /**
         * "memo":"{
         * \"TYPE\":\"ERROR_NO_PACKET\",
         * \"packet_id\":\"420\"
         * }"
         */
        if (isset($memo_arr['TYPE'])) {
            // 此处处理抢红失败的情况。
            if ($memo_arr['TYPE'] != 'ERROR_NO_PACKET') {
                Log::error('红包抢失败的记录出错，msg：' . $msg);
                return '';
            }
            if (!isset($memo_arr['packet_id'])) {
                Log::error('红包抢失败的记录中未找到packet_id，msg：' . $msg);
                return '';
            }
            // 抢红包的eosid
            $packet_id = $memo_arr['packet_id'];
            $outpacketModel = OutPacket::query()->where('eosid', $packet_id)->first();
            if (empty($outpacketModel)) {
                echo "红包未找到";
                Log::error('红包记录未找到，信息为：' . $msg);
                return '';
            }
            $outid = $outpacketModel->id;
            try {
                \DB::beginTransaction();
                // 用户id $userid
                $jiancha_in_packet_fail = InPacket::query()
                    ->where('eosid', $packet_id)
                    ->where('userid', $userid)
                    ->first();
                $entity = null;
                if (empty($jiancha_in_packet_fail)) {
                    // 不存在的情况，直接创建一条
                    $data = [
                        'outid' => $outid,
                        'userid' => $userid,
                        'eosid' => $packet_id,
                        'income_sum' => 0,
                        'is_chailei' => 2,
                        'is_reward' => 1,
                        'reward_type' => 0,
                        'reward_sum' => 0,
                        'own' => 0,
                        'prize_pool' => 0,
                        'txid' => '',
                        'reffee' => 0,
                        'trxid' => $trxid,
                        'status' => 3
                    ];
                    InPacket::create($data);
                } else {
                    // 存在的情况，修改状态为失败的情况。
                    $jiancha_in_packet_fail->status = 3;
                    $jiancha_in_packet_fail->save();
                }
                DB::commit();
            } catch (\Exception $exception) {
                Log::error('事务失败，错误信息为' . $exception->getMessage());
                DB::rollBack();
            }
            return '';
        }
        if (!isset($memo_arr['is_last'])) {
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

        $platform_reserve = 0;
        if (isset($memo_arr['platform_reserve'])) {
            $platform_reserve = $memo_arr['platform_reserve'] / 10000;
        }
        $addr = '';
        if (isset($memo_arr['ref'])) {
            $addr = $memo_arr['ref'];
        }
        $reffee = 0;
        if (isset($memo_arr['reffee'])) {
            $reffee = $memo_arr['reffee'] / 10000;
        }

        try {
            \DB::beginTransaction();
            // 检查此条抢红包记录是否存在
            // $jiancha_in_packet = InPacket::query()->where('txid', $txid)->first();
            $jiancha_in_packet = InPacket::query()
                ->where('eosid', $packet_id)
                ->where('userid', $userid)
                ->first();
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
                $jiancha_in_packet->addr = $addr;
                $jiancha_in_packet->reffee = $reffee;
                $jiancha_in_packet->trxid = $trxid;
                $jiancha_in_packet->status = 2;
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
                    'txid' => $txid,
                    'addr' => $addr,
                    'reffee' => $reffee,
                    'trxid' => $trxid,
                    'status' => 2
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
                $out_in_packet = InPacket::query()->where('outid', $outid)
                    ->where('status', '<=', 2)->get();
                $outPacket_entity = OutPacket::find($outid);
                $outPacket_entity->status = 2;
                $outPacket_entity->surplus_sum = $platform_reserve;
                $outPacket_entity->save();
                $outPacket = $outPacket_entity;
                $out_in_packet_data = array();
                foreach ($out_in_packet as $item => $value) {
                    $out_in_packet_data[$item]['id'] = $value['id'];
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
                $issus_sum_arr = (new OutPacket())->iidexArr;
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
                event(new InPacketEvent(
                    [],
                    $outPacket_data,
                    [],
                    count($out_in_packet_data) >= 10 ? $out_in_packet_data : [],
                    $name,
                    2,
                    $index,
                    $this->getinfo(),
                    json_decode(json_encode(InPacketResource::make($entity)))
                ));
                if (count($out_in_packet_data) >= 10) {
                    foreach ($out_in_packet_data as $k => $v){
                        $entity = InPacket::find($v['id']);
                        $entity->status = 1;
                        $entity->save();
                    }
                    $out = OutPacket::find($outid);
                    $out->is_guangbo = 1;
                    $out->save();
                }
            } else {
                $outPacket_entity = OutPacket::find($outid);
                $name = User::find($outPacket_entity->userid)->name;
                event(new InPacketEvent(
                    [],
                    json_decode(json_encode(OutPacketResource::make($outPacket_entity))),
                    [],
                    [],
                    $name,
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
