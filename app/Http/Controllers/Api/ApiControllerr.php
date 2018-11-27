<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiControllerr extends Controller
{
    public function login(Request $request)
    {
        $username = $request->input('name');
        $password = $request->input('password');
        $query = User::query();
        $query->where('name', '=', $username)->where('password', '=', $password);
    }
}
