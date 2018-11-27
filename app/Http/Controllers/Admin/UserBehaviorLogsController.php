<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\UserBehaviorLogResource;
use App\Models\UserBehaviorLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserBehaviorLogsController extends Controller
{
    public function index(Request $request)
    {
        $query = UserBehaviorLog::query()->with('user');
        if ($request->filled('key')) {
            $name = $request->input('key');
            $query->where(function ($query) use ($name) {
                $query->where('userid', 'like', '%' . $name . '%');
                // $query->orWhere('key', 'like', '%'.$name.'%');
            });
        }
        $list = $query->paginate();
        return view('admin.user_behavior_log.index', compact('list'));
    }

    public function show($id)
    {
        $data = UserBehaviorLog::findOrFail($id);
        return new UserBehaviorLogResource($data);
    }
}
