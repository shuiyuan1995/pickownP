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
        if ($request->filled('eosid')) {
            $query->where('eosid', $request->input('eosid'));
        }
        if ($request->filled('issus_name')) {
            $issus_name = $request->input('issus_name');
            $query->whereHas('out', function ($query) use ($issus_name) {
                $query->whereHas('user', function ($query) use ($issus_name) {
                    $query->where('name', 'like', "%{$issus_name}%");
                });
            });
        }
        if ($request->filled('tail_number')) {
            $tail_number = $request->input('tail_number');
            $query->whereHas('out', function ($query) use ($tail_number) {
                $query->where('tail_number', $tail_number);
            });
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
        $income_sum_count_sum = 0;
        $jianli_sum_count_sum = 0;
        foreach ($list as $value) {
            $income_sum_count_sum += $value->income_sum;
            $jianli_sum_count_sum += $value->reward_sum;
        }
        return view('admin.in_packet.index', compact(
            'list',
            'isChaiLeiArr',
            'isRewardArr',
            'rewardTypeArr',
            'income_sum_count_sum',
            'jianli_sum_count_sum'
        ));
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
