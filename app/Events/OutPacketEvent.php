<?php

namespace App\Events;

use App\Models\OutPacket;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OutPacketEvent extends \Event implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $out_packet;

    /**
     * Create a new event instance.
     *
     * @param OutPacket $outPacket
     */
    public function __construct(OutPacket $outPacket)
    {
        $this->out_packet = $outPacket;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('issus_channel');
    }

    public function broadcastAs()
    {
        return 'issus_packet';
    }
}
