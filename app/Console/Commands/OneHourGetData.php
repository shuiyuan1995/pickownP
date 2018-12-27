<?php

namespace App\Console\Commands;

use App\Events\InPacketEvent;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\InPacketResource;
use App\Models\InPacket;
use App\Models\OutPacket;
use App\Models\TransactionInfo;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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
    protected $description = '获取半个小时前的数据';

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
        $honbao_arr = [];
        $eosparket_key = config('app.eospark_key');
        $url = "https://api.eospark.com/api?module=account&action=get_account_related_trx_info&apikey={$eosparket_key}&account=pickowngames&page=1&size=1&symbol=EOS";
        $data = request_curl($url, [], false, true);
        $dataArr = json_decode($data, true);
        if (!isset($dataArr['errno'])) {
            Log::error('返回数据错误，数据为：' . $data);
            echo "返回数据错误\n";
            return '';
        }
        if ($dataArr['errno'] == 500) {
            Log::error('请求访问报500，数据为：' . $data);
            echo "请求访问报500\n";
            return '';
        }
        if (!isset($dataArr['data'])) {
            Log::error('data字段不存在，数据为：' . $data);
            echo "data字段不存在\n";
            return '';
        }
        if (!isset($dataArr['data']['trace_count'])) {
            Log::error('trace_count字段不存在，数据为：' . $data);
            echo "trace_count字段不存在\n";
            return '';
        }
        // 获取总的条数
        $trace_count = $dataArr['data']['trace_count'];
        // 单页条数 20
        $page_count = 20;
        $count = intval($trace_count / $page_count);
        for ($i = 1; $i <= $count; $i++) {
            $start = time();
            Log::info("第{$i}次请求，请求开始时间：" . date('Y-m-d H:i:s', $start));

            $page = $i;
            $url = "https://api.eospark.com/api?module=account&action=get_account_related_trx_info&apikey={$eosparket_key}&account=pickowngames&page={$page}&size={$page_count}&symbol=EOS";
            $data = request_curl($url, [], false, true);
            $dataArr = json_decode($data, true);
            if (!isset($dataArr['errno'])) {
                Log::error('返回数据错误，数据为：' . $data);
                return '';
            }
            if (!isset($dataArr['data'])) {
                Log::error('data字段不存在，数据为：' . $data);
            }
            if (!isset($dataArr['data']['trace_list'])) {
                Log::error('trace_list字段不存在，数据为：' . $data);
            }
            foreach ($dataArr['data']['trace_list'] as $item => $value) {
                $ttime = strtotime($value['timestamp']);
                // 转换时区后的时间戳
                $tr_time = $ttime + (8 * 60 * 60);
                if ($tr_time < ($time - (60 * 10))) {
                    echo "时间到\n";
                    break 2;
                }
                if (!isset($value['memo'])) {
                    echo 'memo未解析到.' . $data;
                    continue;
                }
                $memo_arr = json_decode($value['memo'], true);
                if (json_last_error() == JSON_ERROR_SYNTAX) {
                    echo '编码错误，不是得json格式的数据' . "\n";
                    continue;
                }
                if (isset($memo_arr['total_remaining'])) {
                    continue;
                } elseif (isset($memo_arr['is_last'])) {
                    $id = $this->successData($value, $tr_time);
                    if ($id !== false) {
                        // 当前抢红包对应的发红包的id
                        $honbao_arr[] = $id;
                    }
                } elseif (isset($memo_arr['TYPE'])) {
                    $this->failData($value, $tr_time);
                }
            }
            echo "第{$i}次请求\n";
            $end = time();
            Log::info("第{$i}次请求，请求结束时间：" . date('Y-m-d H:i:s', $end));
            Log::info("第{$i}次请求，时间长度" . ($end - $start));
            echo "第{$i}次请求结束\n";
            sleep(1);
        }
        // 处理发红包的播报问题
        $honbao_arr_unique = array_unique($honbao_arr);
        foreach ($honbao_arr_unique as $uvalue) {
            $count = InPacket::query()->where('outid', $uvalue)
                ->where('status', 2)
                ->count();
            // 如果当前发红包对应的抢红包的条数大于10，
            if ($count >= 10) {
                $value_InPacket_data = InPacket::query()->where('outid', $uvalue)
                    ->where('status', 2)->get();
                $outPacket = OutPacket::find($uvalue);
                $out_in_packet_data = array();
                foreach ($value_InPacket_data as $item => $value) {
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
                    $out_in_packet_data,
                    $name,
                    2,
                    $index,
                    (new ApiController())->getinfo(),
                    []
                ));

                foreach ($out_in_packet_data as $k => $v) {
                    $entity = InPacket::find($v['id']);
                    $entity->status = 1;
                    $entity->save();
                }
                $out = OutPacket::find($uvalue);
                $out->is_guangbo = 1;
                $out->save();
            }
        }
        echo "处理完毕";
        Log::info('处理完毕');
        return true;
    }

    /** 抢成功那一条数据的处理方法
     * 数据样例
     * array (
     * 'data_md5' => 'c2271f16b3868f8e10b85c5882bcb195',
     * 'trx_id' => 'a5addab9d5340d9a6f08341323fc92031cda221b2ed66b034ba45f2e9978b62d',
     * 'timestamp' => '2018-12-26T03:55:10.500',
     * 'receiver' => 'czstothemoon',
     * 'sender' => 'pickowngames',
     * 'code' => 'eosio.token',
     * 'quantity' => '1.1325',
     * 'memo' => '{
     *      "packet_id":"446",
     *      "user":"czstothemoon",
     *      "own_mined":"30000",
     *      "bomb":"0",
     *      "ref":"bitpie4users",
     *      "reffee":"9",
     *      "luck":"0",
     *      "prize_amount":"0",
     *      "is_last":"0",
     *      "ref":"bitpie4users",
     *      "new_prize_pool":"872",
     *      "packet_amount":"1325",
     *      "txid":"446000006"
     * }',
     * 'symbol' => 'EOS',
     * 'status' => 'executed',
     * 'block_num' => 34055039,
     * )
     * @param $data
     * @param $tr_time
     * @return bool
     */
    public function successData($data, $tr_time)
    {
        $memo_arr = json_decode($data['memo'], true);
        $packet_id = $memo_arr['packet_id'];
        $user = $data['receiver'];
        $out_packet = $this->indexOutPacket($packet_id);
        if (empty($out_packet)) {
            return false;
        }
        $eosid = $out_packet->eosid;
        $outid = $out_packet->id;
        $userid = $this->indexUser($user);
        $trxid = $data['trx_id'];
        $packet_amount = $memo_arr['packet_amount'];
        $luck = $memo_arr['luck'];
        $bomb = $memo_arr['bomb'];
        $prize_amount = $memo_arr['prize_amount'];
        $own_mined = $memo_arr['own_mined'];
        $new_prize_pool = $memo_arr['new_prize_pool'];
        $txid = $memo_arr['txid'];
        $addr = '';
        if (isset($memo_arr['ref'])) {
            $addr = $memo_arr['ref'];
        }
        $is_last = $memo_arr['is_last'];
        $reffee = 0;
        if (isset($memo_arr['reffee'])) {
            $reffee = $memo_arr['reffee'];
        }

        $in_packet_entity = InPacket::query()
            ->where('eosid', $eosid)
            ->where('userid', $userid)
            ->first();
        $entity = null;
        if (!empty($in_packet_entity)) {
            $in_packet_entity->income_sum = $packet_amount / 10000;
            $in_packet_entity->is_chailei = $bomb > 0 ? 1 : 2;
            $in_packet_entity->is_reward = $luck > 0 ? 2 : 1;
            $in_packet_entity->reward_type = $luck;
            $in_packet_entity->reward_sum = $prize_amount / 10000;
            $in_packet_entity->addr = $addr;
            $in_packet_entity->own = $own_mined / 10000;
            $in_packet_entity->prize_pool = $new_prize_pool / 10000;
            $in_packet_entity->txid = $txid;
            $in_packet_entity->trxid = $trxid;
            $in_packet_entity->reffee = $reffee;
            $in_packet_entity->save();
            $entity = $in_packet_entity;
        } else {
            $in_packet_data = [
                'outid' => $outid,
                'userid' => $userid,
                'eosid' => $packet_id,
                'income_sum' => $packet_amount / 10000,
                'is_chailei' => $bomb > 0 ? 1 : 2,
                'is_reward' => $luck > 0 ? 2 : 1,
                'reward_type' => $luck,
                'reward_sum' => $prize_amount / 10000,
                'own' => $own_mined / 10000,
                'prize_pool' => $new_prize_pool / 10000,
                'txid' => $txid,
                'addr' => $addr,
                'reffee' => $reffee,
                'trxid' => $trxid,
                'created_at' => date('Y-m-d H:i:s', $tr_time),
                'status' => 2
            ];
            $entity = InPacket::create($in_packet_data);
        }

        echo 'packet_id:' . $memo_arr['packet_id'] . "\n";
        echo $data['memo'] . "\n";
        echo $data['trx_id'] . "\n";
        echo $data['timestamp'] . "\n";
        echo '转换时区后的时间：' . date('Y-m-d H:i:s', $tr_time) . "\n";
        echo $data['receiver'] . "\n";
        Log::info($data);
        return $entity->outid;
    }

    /**
     * 抢失败那一条数据的处理方法
     * @param $data
     * @param $tr_time
     * @return bool
     */
    public function failData($data, $tr_time)
    {
        $memo_arr = json_decode($data['memo'], true);
        $packet_id = $memo_arr['packet_id'];
        $user = $data['receiver'];
        $out_packet = $this->indexOutPacket($packet_id);
        if (empty($out_packet)) {
            return false;
        }
        $eosid = $out_packet->eosid;
        $outid = $out_packet->id;
        $userid = $this->indexUser($user);
        $trx_id = $data['trx_id'];

        $in_packet_entity = InPacket::query()
            ->where('eosid', $eosid)
            ->where('userid', $userid)
            ->first();
        if (!empty($in_packet_entity)) {
            $in_packet_entity->trxid = $trx_id;
            $in_packet_entity->status = 3;

            $in_packet_entity->save();
        } else {
            $in_packet_data = [
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
                'addr' => '',
                'reffee' => 0,
                'created_at' => date('Y-m-d H:i:s', $tr_time),
                'trxid' => $trx_id,
                'status' => 3
            ];
            InPacket::create($in_packet_data);
        }

        echo 'packet_id:' . $memo_arr['packet_id'] . "\n";
        echo $data['memo'] . "\n";
        echo $data['trx_id'] . "\n";
        echo $data['timestamp'] . "\n";
        echo '转换时区后的时间：' . date('Y-m-d H:i:s', $tr_time) . "\n";
        echo $data['receiver'] . "\n";
        Log::info($data);
        return true;
    }

    /**
     * 获取发红包记录信息。
     * @param $eosid
     * @return bool|\Illuminate\Database\Eloquent\Model|null|object
     */
    public function indexOutPacket($eosid)
    {
        $out_packet_entity = OutPacket::query()->where('eosid', $eosid)->first();
        if (empty($out_packet_entity)) {
            echo "这条发红包记录不存在。\n";
            return null;
        } else {
            return $out_packet_entity;
        }
    }

    /**
     * 获取用户的id
     * @param $name
     * @return mixed
     */
    public function indexUser($name)
    {
        $user_entity = User::query()->where('name', $name)->first();
        if (empty($user_entity)) {
            $data = [
                'name' => $name,
                'publickey' => '',
                'walletid' => '',
                'addr' => '',
                'invite' => '',
                'status' => 1,
            ];
            $entity = User::create($data);
            return $entity->id;
        } else {
            return $user_entity->id;
        }
    }
}
