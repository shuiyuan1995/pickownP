<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeUsersController extends Controller
{
    public function index()
    {
        return view('admin.home_user.index',['list'=>(object)array()]);
    }
}
