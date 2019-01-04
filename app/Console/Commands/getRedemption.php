<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class getRedemption extends Command
{
    /**
     * 获取赎回的信息
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:redemption';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取赎回信息';

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
        echo '命令调用成功';
        return true;
    }

    /**
     * 获取赎回的信息
     */
    public function getTableRows(){

    }

    /**
     * 执行赎回的动作
     */
    public function execRedemption(){

    }
}
