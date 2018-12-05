<?php

namespace App\Http\Controllers\Api;

use App\Events\InPacketEvent;
use App\Events\OutPacketEvent;
use App\Http\Resources\InPacketResource;
use App\Http\Resources\OutPacketResource;
use App\Models\InPacket;
use App\Models\OutPacket;
use App\Models\TransactionInfo;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
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
        $issus_sum_arr = [
            0 => -1,
            1 => 0,
            5 => 1,
            10 => 2,
            20 => 3,
            50 => 4,
            100 => 5
        ];
        $entity = OutPacket::create($request->all());

        $userid = $request->input('userid');
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
        $username = \App\Models\User::find($userid)->name;
        event(new OutPacketEvent($entity, $issus_sum_arr[$issus_sum], $username));
        return $this->success([
            'code' => 200,
            'token' => $request->input('token'),
            'userid' => $request->input('userid')
        ], '发送成功');
    }

    /**
     * outid 发出的红包id
     * userid 用户id
     * eosid 区块链id
     * blocknumber 区块链号
     * income_sum 抢中金额
     * is_chailei 是否踩雷
     * is_reward 是否中奖
     * reward_type 中奖类型
     * reward_sum 中奖金额
     * addr 平台
     * 抢红包记录接口
     * @param Request $request
     * @return $this
     */

    public function income_packet(Request $request)
    {
        $outid = $request->input('outid');
        $userid = $request->input('userid');
        $is_chailei = $request->input('is_chailei');
        $is_reward = $request->input('is_reward');
        $reward_sum = $request->input('reward_sum');
        $eos = $request->input('income_sum');
        $addr = $request->input('addr', 'pc');
        $isnone = $request->input('isnone');

        $entity = InPacket::create($request->all());
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
        if ($is_chailei === 2) {
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
        if ($isnone) {
            event(new InPacketEvent($entity));
        }

        return $this->success([
            'code' => 200,
            'token' => $request->input('token'),
            'userid' => $request->input('userid')
        ], '发送成功');
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
        $userid = $request->input('userid');
        $outpacketsum = OutPacket::where('userid', $userid)->sum('issus_sum');
        $outpacket = OutPacket::where('userid', $userid)->count();
        $chaileicount = TransactionInfo::where('income_userid', $userid)->where('type', 3)->count();
        return OutPacketResource::collection(
            OutPacket::where('userid', $userid)->orderBy('created_at', 'desc')->limit(30)->get()
        )->additional([
            'code' => Response::HTTP_OK,
            'outpacketcount' => $outpacket,
            'chaileicount' => $chaileicount,
            'outpacketsum' => $outpacketsum,
            'message' => ''
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
        $userid = $request->input('userid');
        $pairs = InPacket::where('userid', $userid)->where('reward_type', 1)->count();
        $three = InPacket::where('userid', $userid)->where('reward_type', 2)->count();
        $min = InPacket::where('userid', $userid)->where('reward_type', 3)->count();
        $int = InPacket::where('userid', $userid)->where('reward_type', 4)->count();
        $shunzi = InPacket::where('userid', $userid)->where('reward_type', 5)->count();
        $bomb = InPacket::where('userid', $userid)->where('reward_type', 6)->count();
        $max = InPacket::where('userid', $userid)->where('reward_type', 7)->count();
        $chailei = InPacket::where('userid', $userid)->where('is_chailei', 2)->count();
        return InPacketResource::collection(
            InPacket::where('userid', $userid)->orderBy('created_at', 'desc')->limit(30)->get()
        )->additional([
            'code' => Response::HTTP_OK,
            'paris' => $pairs,
            'three' => $three,
            'min' => $min,
            'int' => $int,
            'shunzi' => $shunzi,
            'bomb' => $bomb,
            'max' => $max,
            'chailei' => $chailei,
            'message' => ''
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
        $outid = $request->input('outid');
        return InPacketResource::collection(
            InPacket::where('outid', $outid)->orderBy('created_at', 'desc')->get()
        )->additional(['code' => Response::HTTP_OK, 'message' => '']);
    }
}
