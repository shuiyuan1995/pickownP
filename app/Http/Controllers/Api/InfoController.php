<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\GamePartitionResource;
use App\Http\Resources\OutPacketResource;
use App\Http\Resources\InPacketResource;
use App\Models\GamePartition;
use App\Models\OutPacket;
use App\Models\InPacket;
use App\Models\TransactionInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class InfoController extends Controller
{
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

    /**
     * 获取可抢的分区下对应的分区可抢红包
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function all_game_gifts(Request $request)
    {
        $gameid = GamePartition::where('status', 1)->first()->id;
        $gameid = $request->input('gameid', $gameid);

        $userid = User::first()->id;
        $userid = $request->input('userid', $userid);

        $query = OutPacket::query();
        $list = $query->where('gameid', $gameid)
            ->where('userid', '!=', $userid)
            ->where('status', '=', 1)
            ->get();

        return OutPacketResource::collection($list)->additional(['code' => Response::HTTP_OK, 'message' => '']);
    }

    /**
     * 获取开启的分区列表接口
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function game_partition()
    {
        $query = GamePartition::query();
        $list = $query->where('status', 1)->get();
        return GamePartitionResource::collection($list)->additional(['code' => Response::HTTP_OK, 'message' => '']);
    }

    public function moneyList(Request $request) {
        $time = date("Y-m-d H:i:s",time());
        $start_time = empty($request->input('start_time')) ? date("Y-m-d 00:00:00",time()): $request->input('start_time');
        $end_time = empty($request->input('end_time')) ? $time : $request->input('end_time');
        $list = DB::select("SELECT `users`.id, `users`.`name`, SUM(`out_packets`.issus_sum) as `num` FROM users
        LEFT JOIN `out_packets` ON `out_packets`.`userid` = `users`.`id` WHERE `out_packets`.`created_at`> ? AND `out_packets`.`created_at`<= ?
        GROUP BY `users`.`id` ORDER BY num DESC",[$start_time,$end_time]);
        $balance = 365.3358;
        // dd(gettype($balance));
        // $this::Curl();
        foreach($list as $k=>$item) {
            if ($k == 0) {
                $item->bonus = bcmul($balance,0.2,4);//精度计算保留4位
            } else if ($k > 0 && $k < 10) {
                $item->bonus = bcmul($balance,0.01,4);
            } else if ($k > 9 && $k < 29) {
                $item->bonus = bcmul($balance,0.0055,4);
            } else {
                $item->bonus = 0;
            }
        }
        dd($list);
    }
    /**
     * 抢红包列表 function
     *
     * @return void
     */
    public function getMoneyList() {
        $query = InPacket::query()->with('user');
        $list = $query->get();
        return InPacketResource::collection($list)->additional(['code' => Response::HTTP_OK, 'message' => '']);
    }


    public static function Curl($url, $data = [], $status ='GTE', $second = 30)
    {
        $post_data = array();
        if(!empty($data)){
            foreach ($data as $key => $value) {
                array_push($post_data, $key.'='.$value);
            }
        }

        $data = join('&', $post_data);

        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch,CURLOPT_URL, $url);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        if($status == 'POST'){
            //post提交方式
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        //运行curl
        $data = curl_exec($ch);
        $data=mb_convert_encoding($data, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5');
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else { 
            $error = curl_errno($ch);
            curl_close($ch);
            return $error;
        }
    }

}
