<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'show_bank_details' => $this->show_bank_details,
            'save_customers' => $this->save_customers,
        ];
    }
}
