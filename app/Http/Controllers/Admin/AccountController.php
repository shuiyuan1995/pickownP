<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    public function index()
    {
        $contract = 0;
        $revenue = 0;
        $reward = 0;
        $mining = 0;
        $airdrop = 0;
        $fenhong = 0;

        return view(
            'admin.account.index',
                compact(
            'contract',
                'revenue',
                'reward',
                'mining',
                'airdrop',
                'fenhong'
            )
        );
    }
}
