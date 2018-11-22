<?php

namespace App\Http\Controllers\Admin;

use App\Models\InPacket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InPacketsController extends Controller
{
    public function index(Request $request)
    {
        $query = InPacket::query();
        if ($request->filled('key')) {
            $name = $request->input('key');
            $query->where(function ($query) use ($name) {
                $query->where('outid', 'like', '%' . $name . '%');
                $query->orWhere('userid', 'like', '%' . $name . '%');
            });
        }

        $list = $query->paginate();
        return view('admin.in_packet.index', compact('list'));
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
