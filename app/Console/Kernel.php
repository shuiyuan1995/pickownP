<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->command('get:one_hour')
             ->everyTenMinutes(); //每十分钟运行一次任务，定时获取抢红包信息
//        $schedule->command('get:ranking_list')
//            ->dailyAt('23:40'); //每天23:40运行任务
//        $schedule->command('get:redemption')
//            ->everyTenMinutes(); //每十分钟运行一次任务，执行赎回操作的任务
    }

    /**
     * Register the commands for the application.
     *
     *
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
