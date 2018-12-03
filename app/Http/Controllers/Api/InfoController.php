<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\GamePartitionResource;
use App\Http\Resources\OutPacketResource;
use App\Models\GamePartition;
use App\Models\OutPacket;
use App\Models\TransactionInfo;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InfoController extends Controller
{
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
}
