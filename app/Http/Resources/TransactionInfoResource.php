<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed typeArr
 * @property mixed statusArr
 * @property mixed issus_userid
 * @property mixed income_userid
 * @property mixed type
 * @property mixed status
 * @property mixed eos
 * @property mixed msg
 * @property mixed created_at
 * @property mixed updated_at
 */
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
            'issus_user' => data_get($this,'issus_user.name','无'),
            'income_userid' => $this->income_userid,
            'income_user' => data_get($this,'income_user.name','无'),
            'type' => $this->type,
            'type_value'=>$type[$this->type],
            'status' => $this->status,
            'status_value'=>$status[$this->status],
            'eos' => $this->eos,
            'msg' => $this->msg,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
