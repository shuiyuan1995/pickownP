<?php

namespace App\Http\Controllers\Admin;

use App\Models\InPacket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InPacketsController extends Controller
{
    public function index(Request $request)
    {
        $query = InPacket::query()->with('user');
        if ($request->filled('id')) {
            $query->where('outid', $request->input('id'));
        }
        if ($request->filled('name')) {
            $name = $request->input('name');
            $query->whereHas('user', function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            });
        }
        if ($request->filled('ischailei')) {
            $query->where('is_chailei', $request->input('ischailei'));
        }

        if ($request->filled('iszhongjiang')) {

            $query->where('is_reward', $request->input('iszhongjiang'));
            if ($request->input('iszhongjiang') == 2 && !empty($request->input('zhongjiangtype'))) {
                $query->where('reward_type', $request->input('zhongjiangtype'));
            }

        }
        if ($request->filled('begin_time')) {
            $key = $request->input('begin_time');
            $query->where('updated_at', '>=', $key);
        }
        if ($request->filled('end_time')) {
            $key = $request->input('end_time');
            $query->where('updated_at', '<=', $key);
        }
        $query->orderBy('created_at', 'desc');
        $in = new InPacket();
        $isChaiLeiArr = $in->is_chailei_arr;
        $isRewardArr = $in->is_reward_arr;
        $rewardTypeArr = $in->rewardTypeArr;

        $list = $query->paginate();
        return view('admin.in_packet.index', compact('list', 'isChaiLeiArr', 'isRewardArr', 'rewardTypeArr'));
    }

    public function edit()
    {
        //
    }

    public function update()
    {
        //
    }
}
