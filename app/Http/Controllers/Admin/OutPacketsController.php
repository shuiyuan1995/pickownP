<?php

namespace App\Http\Controllers\Admin;

use App\Models\OutPacket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OutPacketsController extends Controller
{
    public function index(Request $request)
    {
        $query = OutPacket::query()->with('user');
        if ($request->filled('name')) {
            $name = $request->input('name');
            $query->whereHas('user',function ($q) use ($name) {
                $q->where('name','like','%'.$name.'%');
            });
        }
        if ($request->filled('number')) {
            $number = $request->input('number');
            $query->where('tail_number',$number);
        }
        if ($request->filled('eosid')) {
            $number = $request->input('eosid');
            $query->where('eosid',$number);
        }
        if ($request->filled('status')) {
            $key = $request->input('status');
            $query->where('status',$key);
        }
        if ($request->filled('index')) {
            $key = $request->input('index');
            $query->where('issus_sum',$key);
        }
        if ($request->filled('begin_time')) {
            $key = $request->input('begin_time');
            $query->where('updated_at','>=',$key);
        }
        if ($request->filled('end_time')) {
            $key = $request->input('end_time');
            $query->where('updated_at','<=',$key);
        }
        $query->orderBy('created_at', 'desc');
        $out = new OutPacket();
        $statusArr = $out->statusArr;
        $indexArr = $out->indexArr;
        $list = $query->paginate();
        return view('admin.out_packet.index', compact('list','statusArr','indexArr'));
    }

    public function edit($id)
    {
        $entity = OutPacket::findOrFail($id);
        $out = new OutPacket();
        $statusArr = $out->statusArr;
        return view('admin.out_packet.edit',compact('entity','statusArr'));
    }

    public function update(Request $request,$id)
    {
        $data = OutPacket::findOrFail($id);
        $data->status = $request->input('status');
        $data->save();
        return redirect(route('admin.out_packet.index'))->with('flash_message', '修改成功');
    }
}
