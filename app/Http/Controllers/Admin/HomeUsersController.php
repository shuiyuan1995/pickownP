<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeUsersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        if ($request->filled('key')) {
            $name = $request->input('key');
            $query->where(function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
                $query->orWhere('walletid', 'like', '%' . $name . '%');
            });
        }

        $list = $query->paginate();
        return view('admin.home_user.index', compact('list'));
    }

    public function edit($id)
    {
        $entity = User::findOrFail($id);
        return view('admin.home_user.edit',compact('entity'));
    }

    public function update(Request $request, $id)
    {
        $data = User::findOrFail($id);
        $data->status = $request->input('status');
        $data->last_time = date('Y-m-d H:i:s',time());
        $data->save();
        return redirect(route('admin.home_user.index'))->with('flash_message', '添加成功');

    }
}
