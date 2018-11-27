<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\GamePartitionResource;
use App\Http\Resources\OutPacketResource;
use App\Models\GamePartition;
use App\Models\InPacket;
use App\Models\OutPacket;
use App\Models\TransactionInfo;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class ApiControllerr extends Controller
{
    /**
     * 用户登录接口
     * @param Request $request
     * @return $this
     */
    public function login(Request $request)
    {
        $username = $request->input('name');
        $password = $request->input('password');
        $query = User::query();
        $query->where('name', $username)
            ->where('password', $password);
        return $this->success(['data' => 'ok'], '访问成功！');
    }

    /**
     * 用户发红包接口
     * @param Request $request
     * @return $this
     */
    public function out_packet(Request $request)
    {
        return $this;
    }

    /**
     * 用户抢红包接口
     * @param Request $request
     * @return $this
     */
    public function in_packet(Request $request)
    {
        return $this;
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
     * 获取可用当前分区下当前用户所发出的红包
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function just_mine_game_gifts(Request $request)
    {
        $gameid = GamePartition::first()->id;
        $gameid = $request->input('gameid', $gameid);

        $userid = User::first()->id;
        $userid = $request->input('userid', $userid);

        $query = OutPacket::query();
        $list = $query->where('gameid', $gameid)
            ->where('userid', '=', $userid)
            ->get();
        return OutPacketResource::collection($list)->additional(['code' => Response::HTTP_OK, 'message' => '']);
    }

    /**
     * 获取网站的统计信息
     * @return $this
     */
    public function web_info()
    {
        $outPacketCount = OutPacket::count();
        $transactionInfoCount = TransactionInfo::count();
        $userCount = User::count();
        return $this->success([
            'outPacketCount' => $outPacketCount,
            'transactionInfoCount' => $transactionInfoCount,
            'userCount' => $userCount,
        ]);
    }

    public function rank_reward_list()
    {
        //
    }

    public function allowns_list(Request $request)
    {
        $query = InPacket::query();
        if ($request->filled('time')){
            $query->where('updated_at','>',$request->input('time'));
        }
        return $this->json(['data'=>'ok']);
    }

    public function record_list()
    {
        //
    }
}
