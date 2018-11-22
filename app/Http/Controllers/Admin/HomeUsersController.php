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

    public function edit()
    {
        //
    }

    public function update()
    {
        //
    }
}
