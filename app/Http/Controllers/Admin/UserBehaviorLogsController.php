<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\LoginRecordResource;
use App\Models\LoginRecord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserBehaviorLogsController extends Controller
{
    public function index(Request $request)
    {
        $query = LoginRecord::query()->with('user');
        if ($request->filled('name')) {
            $name = $request->input('name');
            $query->where('user',function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            });
        }
        if ($request->filled('ip')) {
            $ip = $request->input('ip');
            $query->where('ip','like','%'.$ip.'%');
        }
        if ($request->filled('begin_time')) {
            $key = $request->input('begin_time');
            $query->where('updated_at','>=',$key);
        }
        if ($request->filled('end_time')) {
            $key = $request->input('end_time');
            $query->where('updated_at','<=',$key);
        }
        $list = $query->paginate();
        return view('admin.user_behavior_log.index', compact('list'));
    }

    public function show($id)
    {
        $data = LoginRecord::findOrFail($id);
        return new LoginRecordResource($data);
    }
}
