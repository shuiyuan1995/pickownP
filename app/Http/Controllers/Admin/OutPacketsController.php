<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OutPacketsController extends Controller
{
    public function index()
    {
        return view('admin.out_packet.index');
    }
}
