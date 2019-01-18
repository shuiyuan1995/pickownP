<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\InPacketResource;
use App\Http\Resources\OutPacketResource;
use App\Models\InPacket;
use App\Models\LoginRecord;
use App\Models\OutPacket;
use App\Models\TransactionInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

/**
 * 类说明。该类中的方法均为未使用token的方法，可以直接调用。
 * Class InfoController
 * @package App\Http\Controllers\Api
 */
class InfoController extends Controller
{

    /**
     * 用户登录接口
     * @param Request $request
     * @return $this
     */
    public function login(Request $request)
    {
        if (empty($request->input('name', null))) {
            return $this->json([], 2001, '用户名缺失');
        }
        if (empty($request->input('publickey', null))) {
            return $this->json([], 2002, 'publickey缺失');
        }

        $token = md5(microtime());
        $list = User::query()->where('name', $request->input('name'))
            ->first();
        if (empty($list)) {
            $list = User::create($request->all());
        }
        if ($list->status == 2) {
            return $this->json([], 2020, '该用户已关闭');
        }
        Redis::setex('userid:' . $token . ':' . $list->id, 24 * 60 * 60 * 30,
            'userid:' . $list->id . 'token');
        $Logindata = [
            'userid' => $list->id,
            'ip' => $request->getClientIp(),
            'addr' => $request->input('addr'),
        ];
        LoginRecord::create($Logindata);

        return $this->success(['token' => $token . ':' . $list->id], '访问成功！');
    }

    /**
     * 获取网站的统计信息
     * @return $this
     */
    public function getInfo()
    {
        $outPacketCount = OutPacket::count();
        $outPacketSum = OutPacket::sum('issus_sum');
        $inPacketSum = InPacket::query()->with(['out'])->whereHas('out', function ($q) {
            $q->where('status', 2);
        })->sum('income_sum');
        $inPacketCount = InPacket::count();
        $diya_sum = DB::select('SELECT sum(issus_sum) AS sum FROM out_packets ,in_packets WHERE in_packets.outid = out_packets.id');
        $ddiya_jsum = 0;
        foreach ($diya_sum as $value) {
            $ddiya_jsum = $value->sum;
        }
        $transactionInfoCount = TransactionInfo::where('type', '<', 5)->sum('eos') + $ddiya_jsum;
        $userCount = User::count();
        $xinyujiangchientity = InPacket::query()
            ->where('prize_pool', '<>', 0)
            ->orderBy('created_at', 'desc')->first();
        $xinyujiangchi = 0;
        if (!empty($xinyujiangchientity)) {
            $xinyujiangchi = $xinyujiangchientity->prize_pool;
        }
        return $this->success([
            'out_packet_count' => $outPacketCount,
            'transaction_info_count' => (string)$transactionInfoCount,
            'user_count' => $userCount,
            'out_packet_sum' => (string)$outPacketSum,
            'in_packet_sum' => (string)$inPacketSum,
            'in_packet_count' => $inPacketCount,
            'xinyunjiangchi' => empty($xinyujiangchi) ? 0 : $xinyujiangchi,
        ]);
    }

    /**
     * 暂时没有使用在这个函数
     * @param Request $request
     * @return $this
     */
    public function moneyList(Request $request)
    {
        $time = date("Y-m-d H:i:s", time());
        $start_time = empty($request->input('start_time')) ? date("Y-m-d 00:00:00",
            time()) : $request->input('start_time');
        $end_time = empty($request->input('end_time')) ? $time : $request->input('end_time');
        $list = DB::select("SELECT `users`.id, `users`.`name`, SUM(`out_packets`.issus_sum) AS `num` FROM users
        LEFT JOIN `out_packets` ON `out_packets`.`userid` = `users`.`id` WHERE `out_packets`.`created_at`> ? AND `out_packets`.`created_at`<= ?
        GROUP BY `users`.`id` ORDER BY num DESC", [$start_time, $end_time]);
        $balance = 365.3358;
        foreach ($list as $k => $item) {
            if ($k == 0) {
                $item->bonus = bcmul($balance, 0.2, 4);//精度计算保留4位
            } else {
                if ($k > 0 && $k < 10) {
                    $item->bonus = bcmul($balance, 0.01, 4);
                } else {
                    if ($k > 9 && $k < 29) {
                        $item->bonus = bcmul($balance, 0.0055, 4);
                    } else {
                        $item->bonus = 0;
                    }
                }
            }
        }
        return $this->success($list);
    }

    /**
     * 抢红包列表 function
     *
     * @param Request $request
     * @return InfoController
     */
    public function getMoneyList(Request $request)
    {
        $query = OutPacket::query()->with('user');
        $query->where('status', 1);
        $indexArrSwitch = (new OutPacket())->indexArrSwitch;
        $list = $query->orderBy('created_at', 'asc')->get();
        $indexArr = [
            '0.1000' => 0,
            '1.0000' => 1,
            '5.0000' => 2,
            '10.0000' => 3,
            '20.0000' => 4,
            '50.0000' => 5,
            '100.0000' => 6
        ];
        $data = [];

        $yiqianwanhonbao = [];
        foreach ($indexArrSwitch as $i => $v) {
            if ($v === true) {
                $ooooentity = OutPacket::query()->where('is_guangbo', 1)
                    ->where('issus_sum', $i)
                    ->orderBy('updated_at', 'desc')->first();
                if (!empty($ooooentity)) {
                    $yiqianwanhonbao[$indexArr[$i]] = $ooooentity;
                }
            }
        }
        foreach ($yiqianwanhonbao as $item => $value) {
            $data[$indexArr[$value->issus_sum]][$value->id]['index'] = $indexArr[$value->issus_sum];
            $data[$indexArr[$value->issus_sum]][$value->id]['name'] = User::find($value->userid)->name;
            $data[$indexArr[$value->issus_sum]][$value->id]['time'] = strtotime($value->updated_at);
            $data[$indexArr[$value->issus_sum]][$value->id]['num'] = $value->tail_number;
            $data[$indexArr[$value->issus_sum]][$value->id]['in_packet_data'] = InPacketResource::collection(
                InPacket::query()->where('outid', $value->id)
                    ->where('status', '<=', 2)->get()
            );
            $data[$indexArr[$value->issus_sum]][$value->id]['type'] = 2;
            $data[$indexArr[$value->issus_sum]][$value->id]['isgo'] = 1;
        }
        foreach ($list as $item => $value) {
            $data[$indexArr[$value['issus_sum']]][$value['id']]['name'] = $value['user']['name'];
            $data[$indexArr[$value['issus_sum']]][$value['id']]['packetId'] = $value['eosid'];
            $data[$indexArr[$value['issus_sum']]][$value['id']]['txId'] = $value['blocknumber'];
            $data[$indexArr[$value['issus_sum']]][$value['id']]['type'] = 1;
            $data[$indexArr[$value['issus_sum']]][$value['id']]['num'] = $value['tail_number'];
            $data[$indexArr[$value['issus_sum']]][$value['id']]['eos'] = $value['issus_sum'];
            $data[$indexArr[$value['issus_sum']]][$value['id']]['time'] = strtotime($value['created_at']);
            $data[$indexArr[$value['issus_sum']]][$value['id']]['none'] = false;
            $data[$indexArr[$value['issus_sum']]][$value['id']]['index'] = $indexArr[$value['issus_sum']];
            if ($request->header('token')) {
                $userid = substr($request->header('token'), strripos($request->header('token'), ':') + 1);
                $in = InPacket::where('outid', $value['id'])->where('userid', $userid)->count();
                if ($in > 0) {
                    $data[$indexArr[$value['issus_sum']]][$value['id']]['isgo'] = 1;
                } else {
                    $data[$indexArr[$value['issus_sum']]][$value['id']]['isgo'] = 0;
                }
            } else {
                $data[$indexArr[$value['issus_sum']]][$value['id']]['isgo'] = 0;
            }
        }
        $data_d = [];
        foreach ($indexArr as $value) {
            $data_d[$value] = [];
        }
        foreach ($data as $i => $v) {
            foreach ($v as $value) {
                array_push($data_d[$i], $value);
            }
        }
        $jieheentity = new OutPacket();
        $jiehearr = array_combine($jieheentity->indexArr, $jieheentity->indexArrSwitch);
        foreach ($data_d as $item => $value) {
            if ($jiehearr[$item] === false) {
                unset($data_d[$item]);
            }
        }
        $data_dd = array_values($data_d);
        return $this->json($data_dd);
    }

    /**
     * 此方法没有使用但是不知在何处使用过 --- 不要动
     * @param $url
     * @param array $data
     * @param string $status
     * @param int $second
     * @return array|bool|false|int|string|string[]|null
     */
    public static function Curl($url, $data = [], $status = 'GTE', $second = 30)
    {
        $post_data = array();
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                array_push($post_data, $key . '=' . $value);
            }
        }

        $data = join('&', $post_data);

        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($status == 'POST') {
            //post提交方式
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        //运行curl
        $data = curl_exec($ch);
        $data = mb_convert_encoding($data, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5');
        //返回结果
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            return $error;
        }
    }

    /**
     * 获取奖励排序
     */
    public function getRewardCount()
    {
        $jiangjingArr = [
            '0.1000' => 0.0001,
            '1.0000' => 0.0009,
            '5.0000' => 0.0045,
            '10.0000' => 0.009,
            '20.0000' => 0.018,
            '50.0000' => 0.045,
            '100.0000' => 0.09,
        ];

        $userAll = User::all();
        $data = [];
        foreach ($userAll as $valuee) {
            $arr = DB::select('SELECT issus_sum , count(issus_sum) AS count FROM out_packets,in_packets WHERE in_packets.outid = out_packets.id AND in_packets.addr = :addr GROUP BY issus_sum',
                ['addr' => User::find($valuee->id)->name]);
            $sum = 0;
            foreach ($arr as $item => $value) {
                $sum += $jiangjingArr[$value->issus_sum] * $value->count;
            }
            $tixian_sum = TransactionInfo::where('type', 5)->where('income_userid', $valuee->id)->sum('eos');
            $shengyu_sum = $sum - $tixian_sum;
            $out_pakcet_count = OutPacket::where('addr', User::find($valuee->id)->name)->get();
            $out_pakcet_count_data = [];
            foreach ($out_pakcet_count as $value) {
                $out_pakcet_count_data[] = $value->userid;
            }
            $in_pakcet_count = InPacket::where('addr', User::find($valuee->id)->name)->get();
            $in_pakcet_count_data = [];
            foreach ($in_pakcet_count as $value) {
                $in_pakcet_count_data[] = $value->userid;
            }
            $cc = array_keys(array_flip($out_pakcet_count_data) + array_flip($in_pakcet_count_data));
            $data[$valuee->name]['sum'] = $sum;
            $data[$valuee->name]['count'] = count($cc);
            $data[$valuee->name]['tixian_sum'] = empty($tixian_sum) ? 0 : $tixian_sum;
            $data[$valuee->name]['shengyu_sum'] = $shengyu_sum < 0 ? 0 : $shengyu_sum;
        }
        return $this->success($data);
    }

    /**
     * 赎回测试接口
     * @return InfoController
     */
    public function getTableRows()
    {
        if (config('app.env') == 'production'){
            $url = '';
        }else{
//            $url = 'http://35.197.130.214:8888';//测试的url
            $url = 'https://eospro.pickown.com:8888';//测试的url
        }

        $scope = 'pickownbonus';
        $code = 'pickownbonus';
        $table = 'wdowntab';//赎回表表名
        $limit = 40;
        $info = get_table_rows($url, $scope, $code, $table, $limit, null);
        if ($info === false) {
            return $this->json([$info]);
        }
        $info_array = json_decode($info, true);
        return $this->json($info_array);
    }

    /**
     * 抢红包数据表
     * @return InfoController
     */
    public function getPending()
    {
        $url = config('app.eos_interface_addr').':8888';
        $scope = 'pickowngames';
        $code = 'pickowngames';
        $table = 'pending';// 抢红包表表名
        $limit = 100;
        $info = get_table_rows($url, $scope, $code, $table, $limit, null);
        dd($info);
        if ($info === false) {
            return $this->json([$info]);
        }
        $info_array = json_decode($info, true);
        return $this->json($info_array);
    }

    /**
     * 清除日志 -- 已废弃
     * @return mixed
     */
    public function clearLog()
    {
        $logs_dir = str_replace('\\', '/', base_path()) . '/storage/logs/';

        $arr = scandir($logs_dir);
        if (is_array($arr)) {
            foreach ($arr as $item => $value) {
                echo $logs_dir . $value . '<br/>';
                if ($value == '.' || $value == '..' || $value == '.gitignore') {

                } else {
                    return response()->download($logs_dir . $value,
                        config('app.env') . '-' . $value);
                }
            }
        }
        echo $logs_dir;
        return true;
    }

    /**
     * 获取排行榜奖池的接口
     * @return $this
     */
    public function getPaihangbang()
    {
        if (config('app.env') == 'production'){
            $contractabi = 'mcontractabi.php';
        }else{
            $contractabi = 'contractabi.php';
        }
        $addr = config('app.eos_interface_addr');
        $paramArr = [
            "contractName" => "pickowngames",
            "action" => "printboard",
            "params" => []
        ];
        $info = request_curl($addr . '/eosapi/'.$contractabi, $paramArr, true, false);
        $info = trim(trim($info, '<br>'));
        $entity = json_decode($info,true);
        if (isset($entity['data'])){
            return $this->json($entity['data']);
        }
        if (!isset($entity['processed']['action_traces'][0]['console'])){
            return $this->json([],200,'procssed_actions_0_console,不存在');
        }
        $console = json_decode($entity['processed']['action_traces'][0]['console'],true);
        if (!isset($console['data'])){
            return $this->json([]);
        }
        return $this->json($console['data']);

    }


    public function ownseed()
    {
        if (config('app.env') == 'production'){
            $contractabi = 'mcontractabi.php';
        }else{
            $contractabi = 'contractabi.php';
        }
        $url = config('app.eos_interface_addr') . '/eosapi/'.$contractabi;
        $paramArr = [
            "contractName" => "pickownbonus",
            "action" => "ownsend",
            "params" => [0]
        ];
        $info = request_curl($url, $paramArr, true, false);
        $info = trim(trim($info, '<br>'));
        dump(json_decode($info));
//        dd($info);
    }
}
