<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserBehaviorLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $typeArr = $this->typeArr;
        return [
            'id'=>$this->id,
            'userid'=>$this->userid,
            'user'=>$this->user->name,
            'type'=>$this->type,
            'type_value'=>$typeArr[$this->type],
            'msg'=>$this->msg,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at
        ];
    }
}
