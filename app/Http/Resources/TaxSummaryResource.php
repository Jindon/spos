<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaxSummaryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'tax' => $this->tax,
            'taxable_total' => $this->taxable_total,
            'tax_total' => $this->tax_total,
            'sgst' => (float) round(($this->tax_total / 2), 2),
            'cgst' => (float) round(($this->tax_total / 2), 2),
            'sum_total' => $this->sum_total,
        ];
    }
}
