<?php

namespace App\Http\Controllers\Api;

use App\Events\InPacketEvent;
use App\Events\OutPacketEvent;
use App\Http\Resources\InPacketResource;
use App\Http\Resources\OutPacketResource;
use App\Models\InPacket;
use App\Models\OutPacket;
use App\Models\TransactionInfo;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class ApiController extends Controller
{
    public function getinfo()
    {
        $outPacketCount = OutPacket::count();
        $outPacketSum = OutPacket::sum('issus_sum');
        $inPacketSum = InPacket::query()->with(['out'])->whereHas('out', function ($q) {
            $q->where('status', 2);
        })->sum('income_sum');
        $inPacketCount = InPacket::count();
        $transactionInfoCount = TransactionInfo::where('status', '<', 4)->sum('eos');
        $userCount = User::count();
        $xinyujiangchientity = InPacket::orderBy('created_at', 'desc')->first();
        $xinyujiangchi = 0;
        if (!empty($xinyujiangchientity)) {
            $xinyujiangchi = $xinyujiangchientity->prize_pool;
        }
        $data = [
            'out_packet_count' => $outPacketCount,
            'transaction_info_count' => $transactionInfoCount,
            'user_count' => $userCount,
            'out_packet_sum' => $outPacketSum,
            'in_packet_sum' => $inPacketSum,
            'in_packet_count' => $inPacketCount,
            'xinyunjiangchi' => empty($xinyujiangchi) ? 0 : $xinyujiangchi,
        ];
        return $data;
    }

    /**
     * 参数值
     * token
     * userid 用户id
     * issus_sum 金额
     * tail_number 尾号
     * count 个数
     * eosid 区块链id
     * blocknumber 块号
     *
     * addr 平台
     *
     * 发红包接口
     * @param Request $request
     * @return $this
     */
    public function issus_packet(Request $request)
    {
        if (!$request->filled('blocknumber')) {
            return $this->json(['code' => 2004, 'message' => 'blocknumber不存在'], 2004, 'blocknumber不存在');
        }
        $issus_sum_arr = [
            0 => -1,
            1 => 0,
            5 => 1,
            10 => 2,
            20 => 3,
            50 => 4,
            100 => 5
        ];
        $userid = substr($request->header('token'), strripos($request->header('token'), ':') + 1);
        $entity_data = [
            'userid' => $userid,
            'issus_sum' => $request->input('issus_sum', 0),
            'tail_number' => $request->input('tail_number'),
            'count' => $request->input('count'),
            'eosid' => $request->input('eosid'),
            'blocknumber' => $request->input('eosid'),
            'addr' => $request->input('addr'),
            'status' => 1,
            'surplus_sum' => $request->input('issus_sum'),
            'is_guangbo' => 0
        ];
        $entity = OutPacket::create($entity_data);

        $issus_sum = $request->input('issus_sum', 0);
        $addr = $request->input('addr', 'pc');
        $transactionInfo = new TransactionInfo();
        $transactionInfo->issus_userid = $userid;
        $transactionInfo->income_userid = 0;
        $transactionInfo->type = 2;
        $transactionInfo->status = 1;
        $transactionInfo->eos = $issus_sum;
        $transactionInfo->addr = $addr;
        $transactionInfo->save();
        $username = User::find($userid)->name;
        $entityaa['addr'] = $entity->addr;
        $entityaa['blocknumber'] = $entity->blocknumber;
        $entityaa['count'] = $entity->count;
        $entityaa['created_at'] = strtotime($entity->created_at);
        $entityaa['eosid'] = $entity->eosid;
        $entityaa['id'] = $entity->id;
        $entityaa['issus_sum'] = $entity->issus_sum;
        $entityaa['surplus_sum'] = empty($entity->surplus_sum) ? 0 : $entity->surplus_sum;
        $entityaa['tail_number'] = $entity->tail_number;
        $entityaa['userid'] = $entity->userid;


        $data = $this->getinfo();

        event(new OutPacketEvent($entityaa, $issus_sum_arr[$issus_sum], $username, $data));
        return $this->success([
            'code' => 200,
        ], '发送成功');
    }

    /**
     * outid 发出的红包id
     * userid 用户id
     * eosid 区块链id
     * blocknumber 区块链号
     * txid 抢红包的唯一标志
     * income_sum 抢中金额
     * is_chailei 是否踩雷
     * is_reward 是否中奖
     * reward_type 中奖类型
     * reward_sum 中奖金额
     * isnone 是否是最后一个
     * addr 平台
     * 抢红包记录接口
     * @param Request $request
     * @return $this
     */

    public function income_packet(Request $request)
    {
        $outeosid = $request->input('outid');

        $outentity = OutPacket::query()->where('blocknumber', $outeosid)->first();
        if (empty($outentity)) {
            return $this->json(['code' => 2005, 'message' => 'blocknumber对应的红包不存在'], 2005, 'blocknumber对应的红包不存在');
        }
        $outid = $outentity->id;

//        $user = User::where('name',$request->input('addr',null))->first();

//        if(!empty($user)){
//            $qudaojianlidata = [
//                'issus_userid' => 0,
//                'income_userid' => $user->id,
//                'type'=> 6,
//                'status'=>1,
//                'eos'=>OutPacket::find($outid)->issus_sum,
//                'addr'=>User::find($request->input('userid'))->name,
//            ];
//            TransactionInfo::create($qudaojianlidata);
//        }
        $userid = substr($request->header('token'), strripos($request->header('token'), ':') + 1);

        $txid = $request->input('txid');
        try{
            DB::beginTransaction();
            // 查询数据是否在表中存在
            $cunzai_inpacket = InPacket::query()->where('txid', $txid)->lockForUpdate()->first();
            if (!empty($cunzai_inpacket)) {

                DB::commit();
                return $this->success([],'抢红包记录已存在');
            }



        $is_chailei = $request->input('is_chailei');
        $is_reward = $request->input('is_reward');
        $reward_sum = $request->input('reward_sum');
        $eos = $request->input('income_sum');
        $addr = $request->input('addr', '');
        $isnone = $request->input('isnone');
        $InpacketData = [
            'outid' => $outid,
            'userid' => $userid,
            'eosid' => $request->input('eosid'),
            'blocknumber' => $request->input('blocknumber'),
            'income_sum' => $request->input('income_sum'),
            'is_chailei' => $request->input('is_chailei') == 1 ? 1 : 2,
            'is_reward' => $request->input('reward_type') == 0 ? 1 : 2,
            'reward_type' => $request->input('reward_type'),
            'reward_sum' => $request->input('reward_type') == 0 ? 0 : $request->input('reward_sum'),
            'addr' => $request->input('addr'),
            'own' => $request->input('own'),
            'prize_pool' => $request->input('newPrizePool'),
            'txid' => $request->input('txid'),
        ];
        $entity = InPacket::create($InpacketData);


        $income_userid = OutPacket::find($outid)->userid;
        // 抢红包信息
        $data = [
            'issus_userid' => $userid,
            'income_userid' => $income_userid,
            'type' => 1,
            'status' => 1,
            'eos' => $eos,
            'addr' => $addr,
        ];
        TransactionInfo::create($data);

        // 踩雷信息
        if ($is_chailei == 1) {
            $data['issus_userid'] = $income_userid;
            $data['income_userid'] = $userid;
            $data['type'] = 3;
            $data['eos'] = OutPacket::find($outid)->issus_sum;
            TransactionInfo::create($data);
        }

        // 中奖信息
        if ($is_reward !== 0) {
            $data['issus_userid'] = 0;
            $data['income_userid'] = $income_userid;
            $data['type'] = 4;
            $data['eos'] = $reward_sum;
            TransactionInfo::create($data);
        }
        $data = $this->getinfo();
        if ($isnone == 1) {
            // 红包被抢完后生成发红包对用的抢红包的列表
            $out_in_packet = InPacket::query()->where('outid', $outid)->get();
            $out_in_packet_sum = InPacket::query()->where('outid', $outid)->sum('income_sum');
            $outPacket_entity = OutPacket::find($outid);
            $outPacket_entity->status = 2;
            $outPacket_entity->surplus_sum = $outPacket_entity->issus_sum - $out_in_packet_sum;
            $outPacket_entity->save();
            $outPacket = $outPacket_entity;
            $out_in_packet_data = array();
            $reward_data__ = array();
            $chailei_data__ = array();
            foreach ($out_in_packet as $item => $value) {
                $out_in_packet_data[$item]['name'] = User::find($value['userid'])->name;
                $out_in_packet_data[$item]['income_sum'] = $value['income_sum'];
                $out_in_packet_data[$item]['own'] = $value['own'];
                $out_in_packet_data[$item]['is_chailei'] = $value['is_chailei'];
                $out_in_packet_data[$item]['is_reward'] = $value['is_reward'];
                $out_in_packet_data[$item]['reward_type'] = $value['reward_type'];
                $out_in_packet_data[$item]['txid'] = $value['txid'];
                $out_in_packet_data[$item]['reward_sum'] = $value['reward_sum'];
                if ($value['is_chailei'] == 1) {
                    $chailei_data__[$item]['name'] = User::find($value['userid'])->name;
                    $chailei_data__[$item]['chailai_sum'] = $outPacket->issus_sum;
                }
                if ($value['is_reward'] == 2) {
                    $reward_data__[$item]['name'] = User::find($value['userid'])->name;
                    $reward_data__[$item]['reward_type'] = $value['reward_type'];
                    $reward_data__[$item]['reward_sum'] = $value['reward_sum'];
                }

            }

            $reward_data = array_values($reward_data__);
            $chailei_data = array_values($chailei_data__);

            $name = User::find($outPacket->userid)->name;
            $issus_sum_arr = [
                0 => -1,
                1 => 0,
                5 => 1,
                10 => 2,
                20 => 3,
                50 => 4,
                100 => 5
            ];
            $index = $issus_sum_arr[intval($outPacket->issus_sum)];
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
                $reward_data,
                $outPacket_data,
                $chailei_data,
                $out_in_packet_data,
                $name,
                2,
                $index,
                $data,
                    $entity
            ));
            $out = OutPacket::find($outid);
            $out->is_guangbo = 1;
            $out->save();
        } else {
            event(new InPacketEvent(
                [],
                [],
                [],
                [],
                [],
                3,
                [],
                $data,
                $entity
            ));
        }
        DB::commit();
        return $this->success([
            'code' => 200
        ], '发送成功');
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->json([],2013,'查询失败');
        }
    }

    /**
     * 参数值
     * token
     * 用户id userid
     * 当前用户发红包的情况
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function my_issus_packet(Request $request)
    {
        $userid = substr($request->header('token'), strripos($request->header('token'), ':') + 1);
        $outpacketsum = OutPacket::query()->where('userid', $userid)->sum('issus_sum');
        $outpacket = OutPacket::query()->where('userid', $userid)->count();
        $sql = 'SELECT count(DISTINCT out_packets.userid) AS count FROM out_packets,in_packets WHERE out_packets.id = in_packets.outid AND status = 2 AND out_packets.userid = :userid';
        $chailei = DB::select($sql, ['userid' => $userid]);
        $chaileicount = 0;
        foreach ($chailei as $value) {
            $chaileicount = $value->count;
        }
//        $chaileicount = TransactionInfo::where('income_userid', $userid)->where('type', 3)->count();
        $query = OutPacket::query()->where('userid', $userid);
        if ($request->filled('time')) {
            $begin_time = date('Y-m-d 0:0:0', $request->input('time'));
            $end_time = date('Y-m-d 23:59:59', $request->input('time'));
            $query->where('created_at', '>', $begin_time)->where('created_at', '<', $end_time);
        }
        return OutPacketResource::collection(
            $query->where('status', '<>', 1)->orderBy('created_at', 'desc')->paginate()
        )->additional([
            'code' => 200,
            'outpacketcount' => $outpacket,
            'chaileicount' => $chaileicount,
            'outpacketsum' => empty($outpacketsum) ? 0 : $outpacketsum,
            'name' => User::find($userid)->name,
            'last_time' => strtotime(OutPacket::query()->where('userid', $userid)->min('updated_at')),
            'max_time' => strtotime(OutPacket::query()->where('userid', $userid)->max('updated_at')),
            'message' => '发红包列表'
        ]);
    }

    /**
     * 参数值
     * token
     * 用户id userid
     * 当前用户抢红包的情况
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function my_income_packet(Request $request)
    {
        $userid = substr($request->header('token'), strripos($request->header('token'), ':') + 1);


        $pairs = InPacket::query()->with(['out'])->whereHas('out', function ($q) {
            //$q->where('status', 2);
        })->where('userid', $userid)->where('reward_type', 1)->count();
        $three = InPacket::query()->with(['out'])->whereHas('out', function ($q) {
            //$q->where('status', 2);
        })->where('userid', $userid)->where('reward_type', 2)->count();

        $int = InPacket::query()->with(['out'])->whereHas('out', function ($q) {
            //$q->where('status', 2);
        })->where('userid', $userid)->where('reward_type', 3)->count();
        $shunzi = InPacket::query()->with(['out'])->whereHas('out', function ($q) {
            //$q->where('status', 2);
        })->where('userid', $userid)->where('reward_type', 4)->count();
        $bomb = InPacket::query()->with(['out'])->whereHas('out', function ($q) {
            //$q->where('status', 2);
        })->where('userid', $userid)->where('reward_type', 5)->count();

        $chailei = InPacket::query()->with(['out'])->whereHas('out', function ($q) {
            //$q->where('status', 2);
        })->where('userid', $userid)->where('is_chailei', 1)->count();


        $query = InPacket::query()->with(['out'])->whereHas('out', function ($q) {
            //$q->where('status', '=', 2);
        })->where('userid', $userid);
        if ($request->filled('time')) {
            $begin_time = date('Y-m-d 0:0:0', $request->input('time'));
            $end_time = date('Y-m-d 59:59:59', $request->input('time'));
            $query->where('created_at', '>', $begin_time)->where('created_at', '<', $end_time);
        }

        $reward_sum_count = InPacket::query()->where('userid', $userid)->sum('reward_sum');

        return InPacketResource::collection(
            $query->orderBy('created_at', 'desc')->paginate()
        )->additional([
            'code' => 200,
            'paris' => $pairs,
            'three' => $three,
            'int' => $int,
            'shunzi' => $shunzi,
            'bomb' => $bomb,
            'chailei' => $chailei,
            'name' => User::find($userid)->name,
            'packetcount' => InPacket::query()->where('userid', $userid)->count(),
            'packetsum' => (string)(InPacket::query()->with(['out'])->whereHas('out', function ($q) {
//                $q->where('status', 2);
                })->where('userid', $userid)->sum('income_sum') + $reward_sum_count),
            'last_time' => strtotime(InPacket::query()->where('userid', $userid)->min('created_at')),
            'max_time' => strtotime(InPacket::query()->where('userid', $userid)->max('created_at')),
            'message' => '抢红包列表'
        ]);
    }

    /**
     * 参数值
     * token
     * 发出红包id  outid
     * 发出红包领取的情况
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */

    public function red_packet(Request $request)
    {
        $blocknumber = $request->input('outid');
        $outpacketentity = OutPacket::query()->where('blocknumber', $blocknumber)->first();
        if (empty($outpacketentity)) {
            return response()->json([
                'data' => [],
                'code' => 2005,
                'message' => 'blocknumber对应的红包不存在'
            ]);
        }

        $outid = $outpacketentity->id;
        $outuserid = $outpacketentity->userid;
        if ($outpacketentity->status == 1) {
            return response()->json([
                'data' => [],
                'outpacketname' => User::find($outuserid)->name,
                'outpacketsum' => $outpacketentity->issus_sum,
                'outpackettailnumber' => $outpacketentity->tail_number,
                'code' => 2010,
                'message' => '红包未领完'
            ]);
        } else {
            return InPacketResource::collection(
                InPacket::query()->where('outid', $outid)->orderBy('created_at', 'desc')->get()
            )->additional([
                'outpacketname' => User::find($outuserid)->name,
                'outpacketsum' => $outpacketentity->issus_sum,
                'outpackettailnumber' => $outpacketentity->tail_number,
                'code' => 200,
                'message' => '发送成功'
            ]);
        }
    }

    public function postRewardMoney(Request $request)
    {
        $userid = substr($request->header('token'), strripos($request->header('token'), ':') + 1);
        $money = $request->input('money');
        $data = [
            'issus_userid' => 0,
            'income_userid' => $userid,
            'type' => 5,
            'status' => 1,
            'eos' => $money,
            'addr' => User::find($userid)->name,
        ];
        TransactionInfo::create($data);
        return $this->getRewardMoney($request);
    }

    public function getRewardMoney(Request $request)
    {
        $jiangjingArr = [
            1 => 0.0009,
            5 => 0.0045,
            10 => 0.009,
            20 => 0.018,
            50 => 0.045,
            100 => 0.09,
        ];
        $userid = substr($request->header('token'), strripos($request->header('token'), ':') + 1);
        //$getRewardCount = DB::select('SELECT addr,income_userid,sum(eos) AS tixian_count FROM transaction_infos WHERE type = 5 AND income_userid = :income_userid',
        //    ['income_userid' => $userid]);
//        dd($getRewardCount);
        $arr = DB::select('SELECT issus_sum , count(issus_sum) AS count FROM out_packets,in_packets WHERE in_packets.outid = out_packets.id AND in_packets.addr = :addr GROUP BY issus_sum',
            ['addr' => User::find($userid)->name]);
//        dd($arr);
        $sum = 0;
        foreach ($arr as $item => $value) {
            $sum += $jiangjingArr[intval($value->issus_sum)] * $value->count;
        }
//        $tixian_sum = 0;
//        foreach ($getRewardCount as $value) {
//            if (!empty($value->tixian_count)) {
//                $tixian_sum = $value->tixian_count;
//            }
//        }
        //$sum = TransactionInfo::where('type',6)->where('income_userid',$userid)->sum('eos');
        $tixian_sum = TransactionInfo::query()->where('type', 5)->where('income_userid', $userid)->sum('eos');
        $shengyu_sum = $sum - $tixian_sum;
        $out_pakcet_count = OutPacket::query()->where('addr', User::find($userid)->name)->get();
        $out_pakcet_count_data = [];
        foreach ($out_pakcet_count as $value) {
            $out_pakcet_count_data[] = $value->userid;
        }
//        dd($out_pakcet_count_data);
        $in_pakcet_count = InPacket::query()->where('addr', User::find($userid)->name)->get();
        $in_pakcet_count_data = [];
        foreach ($in_pakcet_count as $value) {
            $in_pakcet_count_data[] = $value->userid;
        }
        $cc = array_keys(array_flip($out_pakcet_count_data) + array_flip($in_pakcet_count_data));
        //dd($in_pakcet_count_data);

        return $this->success([
            'sum' => (string)count($cc),
            'shengyu_sum' => (string)($shengyu_sum < 0 ? 0 : $shengyu_sum),
            'tixian_sum' => (string)$tixian_sum
        ], '');
    }

    public function chaxunhongbaozhuangtai(Request $request)
    {


    }

    public function close_packet(Request $request)
    {
        $blocknumber = $request->input('outid');
        $outpacket = OutPacket::query()->where('blocknumber', $blocknumber)->first();
        if (empty($outpacket)) {
            $this->json(['code' => 2005, 'message' => 'blocknumber对应的红包不存在'], 2005, 'blocknumber对应的红包不存在');
        }
        // 红包被抢完后生成发红包对用的抢红包的列表
        $out_in_packet = InPacket::query()->where('outid', $outpacket->id)->get();
        $out_in_packet_sum = InPacket::query()->where('outid', $outpacket->id)->sum('income_sum');
        $outPacket_entity = OutPacket::query()->find($outpacket->id);
        $outPacket_entity->status = 2;
        $outPacket_entity->surplus_sum = $outPacket_entity->issus_sum - $out_in_packet_sum;
        $outPacket_entity->save();
        $outPacket = $outPacket_entity;
        $out_in_packet_data = array();
        $reward_data__ = array();
        $chailei_data__ = array();
        foreach ($out_in_packet as $item => $value) {
            $out_in_packet_data[$item]['name'] = User::find($value['userid'])->name;
            $out_in_packet_data[$item]['income_sum'] = $value['income_sum'];
            $out_in_packet_data[$item]['own'] = $value['own'];
            $out_in_packet_data[$item]['is_chailei'] = $value['is_chailei'];
            $out_in_packet_data[$item]['is_reward'] = $value['is_reward'];
            $out_in_packet_data[$item]['reward_type'] = $value['reward_type'];
            $out_in_packet_data[$item]['reward_sum'] = $value['reward_sum'];
            if ($value['is_chailei'] == 1) {
                $chailei_data__[$item]['name'] = User::find($value['userid'])->name;
                $chailei_data__[$item]['chailai_sum'] = $outPacket->issus_sum;
            }
            if ($value['is_reward'] == 2) {
                $reward_data__[$item]['name'] = User::find($value['userid'])->name;
                $reward_data__[$item]['reward_type'] = $value['reward_type'];
                $reward_data__[$item]['reward_sum'] = $value['reward_sum'];
            }

        }

        $reward_data = array_values($reward_data__);
        $chailei_data = array_values($chailei_data__);

        $name = User::find($outPacket->userid)->name;
        $issus_sum_arr = [
            0 => -1,
            1 => 0,
            5 => 1,
            10 => 2,
            20 => 3,
            50 => 4,
            100 => 5
        ];
        $index = $issus_sum_arr[intval($outPacket->issus_sum)];
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
            $reward_data,
            $outPacket_data,
            $chailei_data,
            $out_in_packet_data,
            $name,
            2,
            $index,
            $this->getinfo()
        ));
        return $this->success(['code' => 200, 'message' => '红包关闭成功'], '红包关闭成功');
    }
}
