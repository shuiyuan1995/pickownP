<?php

namespace App\Http\Controllers\Api;

use App\Events\OutPacketEvent;
use App\Models\OutPacket;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
     * userid
     * issus_sum
     * tail_number
     * count
     * eosid
     * blocknumber
     *
     * 发红包接口
     * @param Request $request
     * @return $this
     */
    public function issus_packet(Request $request)
    {
        $entity = OutPacket::create($request->all());
        event(new OutPacketEvent($entity));
        return $this->success(['code' => 200, 'token' => '', 'userid' => $request->input('userid')], '发送成功');
    }

    public function income_packet(Request $request)
    {

    }

    public function my_issus_packet(Request $request)
    {

    }

    public function my_income_packet(Request $request)
    {

    }

    public function red_packet(Request $request)
    {

    }
}
