<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class WebSocketTestEvent extends \Event implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $socket;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($socket)
    {
        $this->socket = $socket;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    public function broadcastAs()
    {
        return 'server.created';
    }
}
