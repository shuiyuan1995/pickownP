<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed request_id
 * @property mixed userid
 * @property mixed sum
 * @property mixed coin_type
 * @property mixed request_time
 * @property mixed status
 * @property mixed created_at
 * @property mixed updated_at
 */
class RedemptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=> $this->id,
            'request_id'=>$this->request_id,
            'userid'=>$this->userid,
            'sum'=>$this->sum,
            'coin_type'=>$this->coin_type,
            'request_time'=>$this->request_time,
            'status'=>$this->status,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at
        ];
    }
}
