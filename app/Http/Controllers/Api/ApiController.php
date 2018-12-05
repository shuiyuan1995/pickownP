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
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    /**
     * 用户登录接口
     * @param Request $request
     * @return $this
     */
    public function login(Request $request)
    {
        $publickey = $request->input('publickey');
        $list = User::where('publickey', $publickey)->first();
        if (empty($list)) {
            $list = User::create($request->all());
        }
        return $this->success(['data' => ['token' => md5(time()), 'userid' => $list->id]], '访问成功！');
    }

    /**
     * 参数值
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
        $entity = OutPacket::create($request->all());

        $userid = $request->input('userid');
        $issus_sum = $request->input('issus_sum', 0);
        $addr = $request->input('addr', 'pc');
        $transactionInfo = new TransactionInfo();
        $transactionInfo->issus_userid = $userid;
        $transactionInfo->type = 2;
        $transactionInfo->status = 1;
        $transactionInfo->eos = $issus_sum;
        $transactionInfo->addr = $addr;
        $transactionInfo->save();

        event(new OutPacketEvent($entity, 10));
        return $this->success(['code' => 200, 'token' => '', 'userid' => $request->input('userid')], '发送成功');
    }

    /**
     * outid
     * userid
     * eosid
     * blocknumber
     * income_sum
     * is_chailei
     * is_reward
     * reward_type
     * reward_sum
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
        if ($is_reward === 2) {
            $data['issus_userid'] = 0;
            $data['income_userid'] = $income_userid;
            $data['type'] = 4;
            $data['eos'] = $reward_sum;
            TransactionInfo::create($data);
        }

        event(new InPacketEvent($entity));
        return $this->success(['code' => 200, 'token' => '', 'userid' => $request->input('userid')], '发送成功');
    }

    /**
     * 参数值
     * 用户id userid
     * 当前用户发红包的情况
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function my_issus_packet(Request $request)
    {
        $userid = $request->input('userid');
        return OutPacketResource::collection(
            OutPacket::where('userid', $userid)->orderBy('created_at', 'desc')->limit(30)->get()
        )->additional(['code' => Response::HTTP_OK, 'message' => '']);
    }

    /**
     * 参数值
     * 用户id userid
     * 当前用户抢红包的情况
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function my_income_packet(Request $request)
    {
        $userid = $request->input('userid');
        return InPacketResource::collection(
            InPacket::where('userid', $userid)->orderBy('created_at', 'desc')->limit(30)->get()
        )->additional(['code' => Response::HTTP_OK, 'message' => '']);
    }

    /**
     * 参数值
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
