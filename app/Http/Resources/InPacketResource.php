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
        $rewardTypeArr = $this->rewardTypeArr;
        $isChaileiArr = $this->is_chailei_arr;
        return [
            'id' => $this->id,
            'outid' => $this->outid,
            'userid' => $this->userid,
            'user' => $this->user->name,
            'name' => $this->user->name,
            'tail_number'=>$this->out->tail_number,
            'eosid' => $this->eosid,
            'blocknumber' => $this->blocknumber,
            'outblocknumber' => data_get($this,'out.blocknumber',0),
            'income_sum' => $this->income_sum,
            'is_chailei' => $this->is_chailei,
            'is_chailei_value' => $isChaileiArr[$this->is_chailei],
            'outpacket_sum' => data_get($this, 'out.issus_sum', '0'),
            'is_reward' => $this->is_reward,
            'reward_type' => $this->reward_type,
            'reward_type_value' => $rewardTypeArr[$this->reward_type],
            'reward_sum' => $this->reward_sum,
            'addr' => $this->addr,
            'created_at' => strtotime($this->created_at),
            'updated_at' => strtotime($this->updated_at),
            'own'=>$this->own,
        ];
    }
}
