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
            'index'=>$indexArr[(string)$this->issus_sum],
            'tail_number' => $this->tail_number,
            'count' => $this->count,
            'eosid' => $this->eosid,
            'blocknumber' => $this->blocknumber,
            'status' => $this->status,
            'status_value'=>$statusArr[$this->status],
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
