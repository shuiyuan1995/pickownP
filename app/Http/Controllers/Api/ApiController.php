<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\InPacketResource;
use App\Http\Resources\OutPacketResource;
use App\Models\GamePartition;
use App\Models\InPacket;
use App\Models\OutPacket;
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
     * 获取可用当前分区下当前用户所发出的红包
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function just_mine_game_gifts(Request $request)
    {
        $gameid = $request->input('gameid', GamePartition::first()->id);

        $userid = $request->input('userid', User::first()->id);

        $query = OutPacket::query();
        $list = $query->where('gameid', $gameid)
            ->where('userid', '=', $userid)
            ->get();
        return OutPacketResource::collection($list)->additional(['code' => Response::HTTP_OK, 'message' => '']);
    }



    public function rank_reward_list()
    {
        //
    }

    /**
     * 此处time为时间戳
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function allowns_list(Request $request)
    {
        $query = InPacket::query();
        if ($request->filled('time')) {
            $time = $request->input('time');
            $time = date('Y-m-d H:i:s', $time);
            $query->where('updated_at', '>', $time);
        }
        $list = $query->orderBy('updated_at', 'desc')->limit('100')->get();
        return InPacketResource::collection($list)->additional(['code' => Response::HTTP_OK, 'message' => '']);
    }

    /**
     * 当前用户发红包和抢红包
     * @param Request $request
     * @return $this
     */
    public function record_list(Request $request)
    {
        $userid = $request->input('userid', User::first()->id);
        $outQuery = OutPacket::query();
        $outList = $outQuery->where('userid', $userid)
            ->orderBy('updated_at', 'desc')
            ->limit(100)
            ->get();
        $inQuery = InPacket::query();
        $inList = $inQuery->where('userid', $userid)
            ->orderBy('updated_at', 'desc')
            ->limit(100)
            ->get();
        return $this->json([
            'out_list' => OutPacketResource::collection($outList),
            'in_list' => InPacketResource::collection($inList)
        ]);
    }
}
