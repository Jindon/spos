<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'shop_id' => $this->shop_id,
            'name' => $this->name,
            'code' => $this->code,
            'unit_price' => $this->unit_price,
            'tax' => $this->tax,
            'inclusive' => $this->inclusive,
        ];
    }
}
