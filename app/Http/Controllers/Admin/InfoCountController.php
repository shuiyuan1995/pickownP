<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OutPacket;
use App\Models\InPacket;
use App\Models\TransactionInfo;
use Illuminate\Support\Facades\DB;

class InfoCountController extends Controller
{
    public function index(Request $request)
    {
        $time = date("Y-m-d H:i:s", time());
        $start_time = empty($request->input('start_time')) ? date("Y-m-d 00:00:00",time()) : $request->input('start_time');
        $end_time = empty($request->input('end_time')) ? $time : $request->input('end_time');
        if ($request->filled('hour')) {
            $hour = $request->input('hour');
            $start_time = date("Y-m-d H:i:s", time()-(86400));
        }
        if ($request->filled('day')) {
            $hour = $request->input('day');
            $start_time = date("Y-m-d H:i:s", time()-(86400*7));
        }
        if ($request->filled('month')) {
            $hour = $request->input('month');
            $start_time = date("Y-m-d H:i:s", time()-(86400*30));
        }
        // var_dump($start_time);die;
        //注册用户
        $users_count = User::where('created_at', '>', $start_time)->where('created_at', '<=', $end_time)->count();
        //付费用户
        $list = TransactionInfo::query()->where('created_at', '>', $start_time)->where('created_at', '<=', $end_time)->get();
        $list1 = $list->pluck('issus_userid');
        $list2 = $list->pluck('income_userid');
        $paying_count = $list2->merge($list1)->unique()->count();
        //活跃用户
        $active_users_count = DB::select("SELECT COUNT(DISTINCT userid) as num FROM login_records WHERE created_at > :start_time AND created_at <= :end_time", ['start_time' => $start_time, 'end_time'=>$end_time]);
        // var_dump($active_users_count);die;
        //发红包数
        $red_bag_num = OutPacket::where('created_at', '>', $start_time)->where('created_at', '<=', $end_time)->count();
        //交易额
        $money = InPacket::where('created_at', '>', $start_time)->where('created_at', '<=', $end_time)->sum('income_sum');
        return view('admin.info_count.index',compact('users_count','paying_count','active_users_count','red_bag_num','money','start_time','end_time'));
    }
}
