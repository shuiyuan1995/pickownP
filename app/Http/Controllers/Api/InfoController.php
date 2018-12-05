<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\OutPacketResource;
use App\Models\OutPacket;
use App\Models\TransactionInfo;
use App\Models\User;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request;

class InfoController extends Controller
{
    /**
     * 用户登录接口
     * @param Request $request
     * @return $this
     */
    public function login(Request $request)
    {
        $token = md5(time());
        $publickey = $request->input('publickey');
        $list = User::where('publickey', $publickey)->first();
        if (empty($list)) {
            $list = User::create($request->all());
        }
        return $this->success(['data' => ['token' => $token, 'userid' => $list->id]], '访问成功！');
    }

    /**
     * 获取网站的统计信息
     * @return $this
     */
    public function getInfo()
    {
        $outPacketCount = OutPacket::count();
        $transactionInfoCount = TransactionInfo::sum('eos');
        $userCount = User::count();
        return $this->success([
            'out_packet_count' => $outPacketCount,
            'transaction_info_count' => $transactionInfoCount,
            'user_count' => $userCount,
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
        dd($list);
    }

    /**
     * 抢红包列表 function
     *
     * @return InfoController
     */
    public function getMoneyList()
    {

        $query = OutPacket::query()->with('user');
        $list = $query->where('status', 1)->orderBy('created_at','desc')->limit(42)->get();
        $data = [];
        foreach ($list as $item => $value) {
            $data[$item]['packetId'] = $value['eosid'];
            $data[$item]['txId'] = $value['blocknumber'];
            $data[$item]['type'] = 1;
            $data[$item]['num'] = $value['tail_number'];
            $data[$item]['eos'] = $value['issus_sum'];
            $data[$item]['time'] = $value['created_at'];
            $data[$item]['none'] = false;
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

}
