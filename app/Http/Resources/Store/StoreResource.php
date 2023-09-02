<?php

namespace App\Http\Resources\Store;

use App\Http\Resources\User\UserInfo;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $item = $this;
        $result = [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'avatar' => getFileByKey($item, 'avatar'),
            'user' => $this->user ? new UserInfo($this->user) : null
        ];
        return $result;
    }
}
