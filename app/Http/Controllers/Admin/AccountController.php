<?php

namespace App\Http\Controllers\Admin;

use App\Models\WebConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    public function index()
    {
        $data = WebConfig::pluck('content','key');

        $contract = 0;
        $revenue = 0;
        $reward = 0;
        $mining = 0;
        $airdrop = 0;
        $fenhong = 0;

        if(!empty($data)){
           if (!empty($data['eos_pack_api_token'])) {
               if (!empty($data['contract'])){
                   $d = $this->getEos($data['eos_pack_api_token'],$data['contract']);
//                   dd($d);
                   if (!empty($d)){

                       $contract = $d['data']['balance'];
                   }
               }




           }
        }

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
    private function getEos($key,$name){
        $url = "https://api.eospark.com/api?module=account&action=get_account_balance&apikey={$key}&account={$name}";
        $data = request_curl($url,[],false,true);
        if(empty($data)){
            return '';
        }
        return json_decode($data,true);
    }
}
