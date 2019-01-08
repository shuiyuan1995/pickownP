<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed statusArr
 * @property mixed indexArr
 * @property mixed userid
 * @property mixed id
 * @property mixed user
 * @property mixed issus_sum
 * @property mixed tail_number
 * @property mixed count
 * @property mixed eosid
 * @property mixed blocknumber
 * @property mixed status
 * @property mixed addr
 * @property mixed created_at
 * @property mixed updated_at
 * @property mixed inpacket
 * @property mixed surplus_sum
 * @property mixed get_cailei_count
 */
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
        $statusArr = $this->statusArr;
        $indexArr = $this->indexArr;
        return [
            'id' => $this->id,
            'userid' => $this->userid,
            'user' => UserResource::make($this->user),
            'issus_sum' => (string)$this->issus_sum,
            'index' => $indexArr[(string)$this->issus_sum],
            'tail_number' => $this->tail_number,
            'count' => $this->count,
            'eosid' => $this->eosid,
            'blocknumber' => $this->blocknumber,
            'status' => $this->status,
            'status_value' => $statusArr[$this->status],
            'addr' => $this->addr,
            'created_at' => strtotime($this->created_at),
            'updated_at' => strtotime($this->updated_at),
            'inpacket_sum' => count($this->inpacket),
            'surplus_sum'=> $this->surplus_sum,
            'chailei_count' => $this->get_cailei_count
        ];
    }
}
