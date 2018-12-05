<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserCountController extends Controller
{
    public function index()
    {
        return view('admin.user_count.index');
    }
}
