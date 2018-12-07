<?php

namespace App\Http\Controllers\Api;

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
        if (empty($request->input('addr', null))) {
            return $this->json([], 2003, '平台缺失');
        }

        $token = md5(time());

        $publickey = $request->input('publickey', null);
        $list = User::where('publickey', $publickey)->first();
        if (empty($list)) {
            $list = User::create($request->all());
        }
        Redis::setex('userid:' . $list->id . 'token', 2 * 60 * 60, $token);
        $Logindata = [
            'userid' => $list->id,
            'ip' => $request->getClientIp(),
            'addr' => $request->input('addr'),
        ];
        LoginRecord::create($Logindata);

        return $this->success(['data' => ['token' => $token, 'userid' => $list->id, 'invite' => $list->addr]], '访问成功！');
    }

    /**
     * 获取网站的统计信息
     * @return $this
     */
    public function getInfo()
    {
        $outPacketCount = OutPacket::count();
        $outPacketSum = OutPacket::sum('issus_sum');
        $inPacketSum = InPacket::sum('income_sum');
        $inPacketCount = InPacket::count();
        $transactionInfoCount = TransactionInfo::where('status', '<', 4)->sum('eos');
        $userCount = User::count();

        return $this->success([
            'out_packet_count' => $outPacketCount,
            'transaction_info_count' => $transactionInfoCount,
            'user_count' => $userCount,
            'out_packet_sum' => $outPacketSum,
            'in_packet_sum' => $inPacketSum,
            'in_packet_count' => $inPacketCount,
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
        $list = $query->where('status', 1)->orderBy('created_at', 'desc')->limit(42)->get();
        $indexArr = [
            '1.0000' => 0,
            '5.0000' => 1,
            '10.0000' => 2,
            '20.0000' => 3,
            '50.0000' => 4,
            '100.0000' => 5
        ];
        $data = [];
        foreach ($list as $item => $value) {
            $data[$item]['name'] = $value['user']['name'];
            $data[$item]['packetId'] = $value['eosid'];
            $data[$item]['txId'] = $value['blocknumber'];
            $data[$item]['type'] = 1;
            $data[$item]['num'] = $value['tail_number'];
            $data[$item]['eos'] = $value['issus_sum'];
            $data[$item]['time'] = $value['created_at'];
            $data[$item]['none'] = false;
            $data[$item]['index'] = $indexArr[$value['issus_sum']];
            if ($request->filled('userid')) {
                $userid = $request->input('userid');
                $in = InPacket::where('outid', $value['id'])->where('userid', $userid)->count();
                if ($in > 0) {
                    $data[$item]['isgo'] = 1;
                } else {
                    $data[$item]['isgo'] = 0;
                }
            } else {
                $data[$item]['isgo'] = 0;
            }


//            if ($value['issus_sum'] == '1.0000') {
//                $data[0][] = $value;
//            } elseif ($value['issus_sum'] == '5.0000') {
//                $data[1][] = $value;
//            } elseif ($value['issus_sum'] == '10.0000') {
//                $data[2][] = $value;
//            } elseif ($value['issus_sum'] == '20.0000') {
//                $data[3][] = $value;
//            } elseif ($value['issus_sum'] == '50.0000') {
//                $data[4][] = $value;
//            } elseif ($value['issus_sum'] == '100.0000') {
//                $data[5][] = $value;
//            }
        }
        return $this->json($data);
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
            1 => 0.0009,
            5 => 0.0045,
            10 => 0.009,
            20 => 0.018,
            50 => 0.045,
            100 => 0.09,
        ];
        $sql = 'SELECT addr,income_userid,sum(eos) AS sum_eos FROM transaction_infos WHERE status= 5 GROUP BY income_userid';
        $tixianArr = DB::select($sql);

        $packetSql = 'select in_packets.id as iid,out_packets.id as oid from in_packets JOIN out_packets ON outid = out_packets.id GROUP BY out_packets.userid';
        dd(DB::select($packetSql));
    }
}
