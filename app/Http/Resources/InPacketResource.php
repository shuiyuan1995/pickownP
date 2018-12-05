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
        $status = $this->statusArr;
        return [
            'id' => $this->id,
            'outid' => $this->outid,
            'sum' => $this->sum,
            'packet_tail_number' => $this->packet_tail_number,
            'is_win' => $this->is_win,
            'status' => $status[$this->status],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}