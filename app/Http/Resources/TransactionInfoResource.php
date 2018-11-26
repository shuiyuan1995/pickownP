<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $type = $this->typeArr;
        $status = $this->statusArr;
        return [
            'id' => $this->id,
            'issus_userid' => $this->issus_userid,
            'issus_user' => $this->issus_user->name,
            'income_userid' => $this->income_userid,
            'income_user' => $this->income_user->name,
            'type' => $this->type,
            'type_value'=>$type[$this->type],
            'status' => $this->status,
            'status_value'=>$status[$this->status],
            'eos' => $this->eos / 10000,
            'issus_count_sum' => $this->issus_count_sum / 10000,
            'msg' => $this->msg,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
