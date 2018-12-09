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
            $query->where('user',function ($q) use ($name) {
                $q->where('name','like','%'.$name.'%');
            });
        }
        if ($request->filled('number')) {
            $number = $request->input('number');
            $query->where('tail_number',$number);
        }
        if ($request->filled('status')) {
            $key = $request->input('status');
            $query->where('status',$key);
        }
        if ($request->filled('index')) {
            $key = $request->input('index');
            $query->where('issus_sum',$key);
        }
        $out = new OutPacket();
        $statusArr = $out->statusArr;
        $indexArr = $out->indexArr;
        $list = $query->paginate();
        return view('admin.out_packet.index', compact('list','statusArr','indexArr'));
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
