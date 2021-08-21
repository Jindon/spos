<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'shop_id' => $this->shop_id,
            'name' => $this->name,
            'address' => $this->address,
            'state_id' => $this->state_id,
            'state' => $this->state,
            'gstin' => $this->gstin,
            'pan' => $this->pan,
            'type' => $this->type,
        ];
    }
}
