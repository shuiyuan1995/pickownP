<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OutPacketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'gameid' => $this->gameid,
            'game' => $this->game->name,
            'userid' => $this->userid,
            'user' => $this->user->name,
            'seed_sum' => (string)($this->seed_sum / 10000),
            'number' => $this->number,
            'surplus_sum' => $this->surplus_sum,
            'count' => $this->count,
            'up' => (string)($this->up / 100),
            'down' => (string)($this->down / 100),
            'surplus_count' => $this->surplus_count,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
