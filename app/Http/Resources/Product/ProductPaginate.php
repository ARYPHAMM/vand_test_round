<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductPaginate extends JsonResource
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
            'data' => ProductResource::collection($this->items()),
            "current_page" => $this->currentPage(),
            'last_page' => $this->lastPage(),
            'total' =>$this->total(),
            'lastPage' =>$this->lastPage(),
            'perPage' =>$this->perPage(),
        ];
    }
}
