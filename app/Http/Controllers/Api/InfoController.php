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

        $publickey = $request->input('publickey', null);
        $list = User::query()->where('name', $request->input('name'))
            ->first();
        if (empty($list)) {
            $list = User::create($request->all());
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
        $xinyujiangchientity = InPacket::orderBy('created_at', 'desc')->first();
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
        // dd(gettype($balance));
        // $this::Curl();
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
        $list = $query->where('status', 1)->orderBy('created_at', 'asc')->get();
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

        // 已抢完的最新红包
        $yiqianwanhonbao = OutPacket::query()->where('status', 2)
            ->groupBy('issus_sum')->orderBy('updated_at', 'desc')->get();
        // 已抢完的最新红包对应的抢的列表
        $yiqianwanhonbaolist = [];
        foreach ($yiqianwanhonbao as $item => $value) {
            $data[$indexArr[$value['issus_sum']]][$value['id']]['index'] = $indexArr[$value['issus_sum']];
            $data[$indexArr[$value['issus_sum']]][$value['id']]['name'] = User::find($value['userid'])->name;
            $data[$indexArr[$value['issus_sum']]][$value['id']]['time'] = strtotime($value['updated_at']);
            $data[$indexArr[$value['issus_sum']]][$value['id']]['tail_number'] = $value['tail_number'];
            $data[$indexArr[$value['issus_sum']]][$value['id']]['in_packet_data'] = InPacketResource::collection(
                InPacket::query()->where('outid', $value['id'])->get()
            );
            $data[$indexArr[$value['issus_sum']]][$value['id']]['type'] = 2;
            $data[$indexArr[$value['issus_sum']]][$value['id']]['isgo'] = 1;
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

        return $this->json($data_d);
    }


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
//        $sql = 'SELECT addr,income_userid,sum(eos) AS tisum FROM transaction_infos WHERE status = 5 GROUP BY income_userid';
//        $tixianArr = DB::select($sql);
//        $packetSql = 'SELECT in_packets.id AS iid,out_packets.id AS oid FROM in_packets JOIN out_packets ON outid = out_packets.id GROUP BY in_packets.userid';
        //dd(DB::select($packetSql));
//        $inPacketName = DB::select('SELECT addr, count(addr) AS count FROM in_packets GROUP BY addr');
        //dd($inPacketName);
//        $outPacketName = DB::select('SELECT addr, count(addr) AS count FROM out_packets GROUP BY addr');
        //dd($outPacketName);
//        $outPacketName = OutPacket::all();
//        dd($outPacketName);
//        $data = [];
//        foreach ($outPacketName as $value){
//            $data[empty($value->addr)? 0:$value->addr]['issus_sum'] += $jiangjingArr[intval($value->issus_sum)];
//        }
        $userAll = User::all();
        $data = [];
        foreach ($userAll as $valuee) {
//            $getRewardCount = DB::select('SELECT addr,income_userid,sum(eos) AS tixian_count FROM transaction_infos WHERE type = 5 AND income_userid = :income_userid',
//                ['income_userid' => $valuee->id]);
//        dd($getRewardCount);
            $arr = DB::select('SELECT issus_sum , count(issus_sum) AS count FROM out_packets,in_packets WHERE in_packets.outid = out_packets.id AND in_packets.addr = :addr GROUP BY issus_sum',
                ['addr' => User::find($valuee->id)->name]);
//        dd($arr);
            $sum = 0;
            foreach ($arr as $item => $value) {
                $sum += $jiangjingArr[$value->issus_sum] * $value->count;
            }
//
            $tixian_sum = TransactionInfo::where('type', 5)->where('income_userid', $valuee->id)->sum('eos');
            $shengyu_sum = $sum - $tixian_sum;
            $out_pakcet_count = OutPacket::where('addr', User::find($valuee->id)->name)->get();
            $out_pakcet_count_data = [];
            foreach ($out_pakcet_count as $value) {
                $out_pakcet_count_data[] = $value->userid;
            }
//        dd($out_pakcet_count_data);
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
}
