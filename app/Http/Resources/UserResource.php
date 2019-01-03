<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed name
 * @property mixed status
 * @property mixed publickey
 * @property mixed created_at
 * @property mixed updated_at
 * @property mixed invite
 * @property mixed addr
 * @property mixed walletid
 */
class UserResource extends JsonResource
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
            'status' => $this->status,
            'publickey' => $this->publickey,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'  => $this->updated_at->format('Y-m-d H:i:s'),
            'invite' => $this->invite,
            'addr' => $this->addr,
            'walletid' => $this->walletid,
        ];
    }
}
