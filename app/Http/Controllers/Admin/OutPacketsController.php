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
        if ($request->filled('key')) {
            $name = $request->input('key');
            $query->where(function ($query) use ($name) {
                $query->where('gameid', 'like', '%' . $name . '%');
                $query->orWhere('userid', 'like', '%' . $name . '%');
            });
        }

        $list = $query->paginate();
        return view('admin.out_packet.index', compact('list'));
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
