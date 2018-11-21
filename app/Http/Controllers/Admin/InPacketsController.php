<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InPacketsController extends Controller
{
    public function index()
    {
        return view('admin.in_packet.index');
    }
}
