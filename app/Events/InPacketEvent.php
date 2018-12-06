<?php

namespace App\Events;

use App\Models\InPacket;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class InPacketEvent extends \Event implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $in_packet;
    public $out_packet;

    /**
     * Create a new event instance.
     *
     * @param InPacket $inPacket
     * @param $out_packe
     */
    public function __construct($inPacket,$out_packe)
    {
        $this->in_packet = $inPacket;
        $this->out_packet = $out_packe;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('income_channel');
    }

    public function broadcastAs()
    {
        return 'income_packet';
    }
}
