<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InfoCountController extends Controller
{
    public function index()
    {
        return view('admin.info_count.index');
    }
}
