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

    public function moneyList()
    {

    }

    /**
     * 抢红包列表 function
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getMoneyList()
    {
        $query = InPacket::query()->with('user');
        $list = $query->get();
        return InPacketResource::collection($list)->additional(['code' => Response::HTTP_OK, 'message' => '']);
    }
}
