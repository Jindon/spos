<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'unit_price' => (float) $this->unit_price,
            'quantity' => (float) $this->quantity,
            'tax' => (float) $this->tax,
            'inclusive' => $this->inclusive,
            'taxable_amount' => (float) $this->taxable_amount,
            'tax_amount' => (float) $this->tax_amount,
            'total' => (float) $this->total,
        ];
    }
}
