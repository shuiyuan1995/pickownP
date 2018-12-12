<?php

namespace App\Http\Controllers\Admin;

use App\Models\WebConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    public $data;

    public function index()
    {
        $this->data = WebConfig::pluck('content', 'key');

        $contract = 0;
        $contractname = '';
        $revenue = 0;
        $revenuename = '';
        $reward = 0;
        $rewardname = '';
        $mining = 0;
        $miningname = '';
        $airdrop = 0;
        $airdropname = '';
        $fenhong = 0;
        $fenhongname = '';

        if (!empty($this->data) && !empty($this->data['eos_pack_api_token'])) {
            $temp = $this->panding('contract');
            if (!empty($temp)) {
                $contract = $temp['index'];
                $contractname = $temp['name'];
            }
            $temp = null;
            $temp = $this->panding('revenue');
            if (!empty($temp)) {
                $revenue = $temp['index'];
                $revenuename = $temp['name'];
            }
            $temp = null;
            $temp = $this->panding('reward');
            if (!empty($temp)) {
                $reward = $temp['index'];
                $rewardname = $temp['name'];
            }
            $temp = null;
            $temp = $this->panding('mining');
            if (!empty($temp)) {
                $mining = $temp['index'];
                $miningname = $temp['name'];
            }
            $temp = null;
            $temp = $this->panding('airdrop');
            if (!empty($temp)) {
                $airdrop = $temp['index'];
                $airdropname = $temp['name'];
            }
            $temp = null;
            $temp = $this->panding('fenhong');
            if (!empty($temp)) {
                $fenhong = $temp['index'];
                $fenhongname = $temp['name'];
            }

        }

        return view(
            'admin.account.index',
            compact(
                'contract',
                'contractname',
                'revenue',
                'revenuename',
                'reward',
                'rewardname',
                'mining',
                'miningname',
                'airdrop',
                'airdropname',
                'fenhong',
                'fenhongname'
            )
        );
    }

    private function panding($name)
    {
//        dump($name);
        //dump($this->data->contract);
        $data = [];
//        dd(data_get($this->data,$name));
        if (!empty(data_get($this->data, $name, 0))) {

            $d = $this->getEos($this->data['eos_pack_api_token'], $this->data[$name]);
            if (!empty($d)) {
                if (!empty($d['data']['balance'])) {
                    $data['index'] = $d['data']['balance'];
                    $data['name'] = data_get($this->data, $name);
                }
            }
        }
        return $data;
    }

    private function getEos($key, $name)
    {
        $url = "https://api.eospark.com/api?module=account&action=get_account_balance&apikey={$key}&account={$name}";
        $data = request_curl($url, [], false, true);
        if (empty($data)) {
            return '';
        }
        return json_decode($data, true);
    }
}
