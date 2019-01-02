<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed rewardTypeArr
 * @property mixed is_chailei_arr
 * @property mixed id
 * @property mixed outid
 * @property mixed userid
 * @property mixed out
 * @property mixed eosid
 * @property mixed blocknumber
 * @property mixed income_sum
 * @property mixed is_chailei
 * @property mixed is_reward
 * @property mixed reward_type
 * @property mixed reward_sum
 * @property mixed addr
 * @property mixed created_at
 * @property mixed updated_at
 * @property mixed own
 * @property mixed txid
 */
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
            'user' => data_get($this,'user.name','未知用户'),
            'name' => data_get($this,'user.name','未知用户'),
            'tail_number'=>$this->out->tail_number,
            'eosid' => $this->eosid,
            'blocknumber' => $this->blocknumber,
            'outblocknumber' => data_get($this,'out.blocknumber',0),
            'income_sum' => (string)$this->income_sum,
            'is_chailei' => $this->is_chailei,
            'is_chailei_value' => $isChaileiArr[$this->is_chailei],
            'outpacket_sum' => (string)data_get($this, 'out.issus_sum', '0'),
            'is_reward' => $this->is_reward,
            'reward_type' => $this->reward_type,
            'reward_type_value' => $rewardTypeArr[$this->reward_type],
            'reward_sum' => (string)$this->reward_sum,
            'addr' => $this->addr,
            'created_at' => strtotime($this->created_at),
            'updated_at' => strtotime($this->updated_at),
            'own'=>(string)$this->own,
            'txid'=>$this->txid,
        ];
    }
}
