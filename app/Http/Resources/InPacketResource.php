<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InPacketResource extends JsonResource
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
            'outid' => $this->outid,
            'userid' => $this->userid,
            'user'=> UserResource::make($this->user),
            'eosid' => $this->eosid,
            'blocknumber'=> $this->blocknumber,
            'income_sum' => $this->income_sum,
            'is_chailei' => $this->is_chailei,
            'is_reward'  => $this->is_reward,
            'reward_type' => $this->reward_type,
            'reward_sum' => $this->reward_sum,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
