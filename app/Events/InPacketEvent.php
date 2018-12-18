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

    public $reward_data;
    public $out_packet;
    public $in_packet_data;
    public $chailei_data;
    public $name;
    public $type;
    public $index;
    public $info;
    public $dantiao_in_packet;

    /**
     * Create a new event instance.
     *
     * @param $reward_data
     * @param $out_packe
     * @param $chailei_data
     * @param $in_packet_data
     * @param $name
     * @param $type
     * @param $index
     * @param $info
     * @param $dantiao_in_packet
     * @internal param $rewrd_data
     */
    public function __construct(
        $reward_data,
        $out_packe,
        $chailei_data,
        $in_packet_data,
        $name,
        $type,
        $index,
        $info,
        $dantiao_in_packet
    ) {
        $this->reward_data = $reward_data;
        $this->out_packet = $out_packe;
        $this->chailei_data = $chailei_data;
        $this->in_packet_data = $in_packet_data;
        $this->name = $name;
        $this->type = $type;
        $this->index = $index;
        $this->info = $info;
        $this->dantiao_in_packet = $dantiao_in_packet;
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
