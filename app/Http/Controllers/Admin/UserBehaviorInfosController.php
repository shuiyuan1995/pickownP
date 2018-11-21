<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserBehaviorInfosController extends Controller
{
    public function index()
    {
        return view('admin.user_behavior_info.index');
    }
}
