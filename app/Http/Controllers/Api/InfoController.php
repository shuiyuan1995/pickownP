<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\OutPacketResource;
use App\Models\OutPacket;
use App\Models\TransactionInfo;
use App\Models\User;
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
        $query = OutPacket::query()->with('user');
        $list = $query->where('status',1)->get();
        return OutPacketResource::collection($list)->additional(['code' => Response::HTTP_OK, 'message' => '']);
    }
}
