<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Option\GroupForOptionForShoppingResource;
use App\Http\Resources\ProductSubCategory\ProductSubCategoryRerouce;
use App\Http\Resources\Shop\ShopResource;
use App\Http\Resources\Store\StoreResource;
use App\Infrastructure\Eloquent\Product\GroupForOption;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'avatar' => getFileByKey($this, 'avatar'),
            'store' => $this->store ? new StoreResource($this->store) : null
        ];
        return $result;
    }
}
