<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed name
 * @property mixed sum
 * @property mixed up
 * @property mixed down
 * @property mixed number
 * @property mixed status
 * @property mixed created_at
 * @property mixed updated_at
 */
class GamePartitionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sum' => $this->sum,
            'up' => $this->up,
            'down' => $this->down,
            'number' => $this->number,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
