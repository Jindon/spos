<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'name' => $this->name,
            'address' => $this->address,
            'phone' => $this->phone,
            'alt_phone' => $this->alt_phone,
            'email' => $this->email,
            'gstin' => $this->gstin,
            'state' => $this->state,
            'bank_name' => $this->bank_name,
            'bank_branch' => $this->bank_branch,
            'bank_ifsc' => $this->bank_ifsc,
            'bank_account' => $this->bank_account,
        ];
    }
}
