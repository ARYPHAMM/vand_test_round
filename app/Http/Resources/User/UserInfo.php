<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Customer\CustomerResource;
use App\Http\Resources\Shipper\ShipperResource;
use App\Http\Resources\Shop\ShopResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserInfo extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = $this;
        $result = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            // 'email_verified_at'=> $this->email_verified_at,
            'created_at' => $this->created_at,
            'avatar'=>getFileByKey($user,'avatar')
        ];
        if (key_exists('token', $this->additional)) {
            $result['token'] = $this->additional['token'];
        }
        return $result;
    }
}
