<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed userid
 * @property mixed addr
 * @property mixed ip
 * @property mixed created_at
 * @property mixed updated_at
 */
class LoginRecordResource extends JsonResource
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
            'id'=>$this->id,
            'userid'=>$this->userid,
            'user'=>data_get($this,'user.name','无'),
            'addr'=>$this->addr,
            'ip'=>$this->ip,
            'created_at'=>$this->created_at->format('Y-m-d H:i:s'),
            'updated_at'=>$this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
