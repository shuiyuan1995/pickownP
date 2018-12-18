<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ratchet\Client\WebSocket;
use Ratchet\RFC6455\Messaging\MessageInterface;
use React\EventLoop\Factory;
use React\Socket\Connector;

class SeedWebSocket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:websocket';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '监听网站';

    /**
     * Create a new command instance.
     *
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
        $loop = Factory::create();
        $reactConnector = new Connector($loop, [
            'dns' => '8.8.8.8',
            'timeout' => 10
        ]);
        $connector = new \Ratchet\Client\Connector($loop, $reactConnector);

        $connector('wss://ws.eospark.com/v1/ws?apikey=43222c2a30238d8ed72d60c033a7a7e0', [], ['Origin' => 'http://localhost'])
            ->then(function(WebSocket $conn) {
                $conn->on('message', function(MessageInterface $msg) use ($conn) {
                    $data = json_decode($msg,true);
                    foreach ($data as $item => $v){
                        echo $item."\n";
                    }
                });

                $conn->on('close', function($code = null, $reason = null) {
                    echo "Connection closed ({$code} - {$reason})\n";
                });

                $conn->send('{"msg_type": "subscribe_account","name": "pickowngames"}');
            }, function(\Exception $e) use ($loop) {
                echo "Could not connect: {$e->getMessage()}\n";
                $loop->stop();
            });
        $loop->run();
        return ;
    }
}
