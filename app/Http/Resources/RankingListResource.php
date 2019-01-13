<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed balance
 * @property mixed prize
 * @property mixed ranking
 * @property mixed created_at
 * @property mixed updated_at
 */
class RankingListResource extends JsonResource
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
            'balance'=>$this->balance,
            'prize'=>$this->prize,
            'ranking'=>$this->ranking,
            'created_at'=>strtotime($this->created_at),
            'updated_at'=>strtotime($this->updated_at)
        ];
    }
}
