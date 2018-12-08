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
        ];
    }
}
