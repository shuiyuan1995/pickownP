<?php

namespace App\Console\Commands;

use App\Models\RankingList;
use App\Models\User;
use Illuminate\Console\Command;

class getRankingList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:ranking_list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '定时获取排行榜数据，并将排行榜的数据放入表中';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $addr = config('app.eos_interface_addr');
        $paramArr = [
            "contractName" => "pickowngames",
            "action" => "printboard",
            "params" => []
        ];
        $info = request_curl($addr . '/eosapi/contractabi.php', $paramArr, true, false);
        $info = trim(trim($info, '<br>'));
        $entity = json_decode($info, true);
        if (isset($entity['data'])) {
            $data = $entity['data'];
        } else {
            if (!isset($entity['processed']['action_traces'][0]['console'])) {
                return 'procssed_actions_0_console,不存在';
            }
            $console = json_decode($entity['processed']['action_traces'][0]['console'], true);
            if (!isset($console['data'])) {
                return false;
            }
            $data = $console['data'];
        }

        foreach ($data as $k => $v) {
            echo ($k + 1) . ":\n";
            $user = $v['user'];
            echo $user."\n";
            $userEntity = User::query()->where('name', $user)->first();
//            dump($userEntity);
            if (empty($userEntity)) {
                $userData = [
                    'name' => $user,
                    'publickey' => '',
                    'walletid' => '',
                    'addr' => '',
                    'invite' => '',
                    'status' => 1
                ];
                $userEntity = User::create($userData);
            }
            $userid = $userEntity->id;

//            dump($userid);
            $data = [
                'userid' => $userid,
                'balance' => $v['balance'] / 10000,
                'prize' => $v['prize'] / 10000,
                'ranking' => $k + 1
            ];
            RankingList::create($data);
        }
        return true;
    }
}
