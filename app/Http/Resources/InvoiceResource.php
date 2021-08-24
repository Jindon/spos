<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'invoice_no' => $this->invoice_no,
            'invoice_date' => $this->invoice_date,
            'taxable' => (float) $this->taxable,
            'tax' => (float) $this->tax,
            'total' => (float) $this->total,
            'discount' => (float) $this->discount,
            'cgst' => (float) $this->cgst,
            'sgst' => (float) $this->sgst,
            'walk_in_customer' => $this->walk_in_customer,
            'customer_name' => $this->customer_name,
            'customer_address' => $this->customer_address,
            'customer_gstin' => $this->customer_gstin,
            'customer_pan' => $this->customer_pan,
            'state' => $this->state,
            'items' => InvoiceItemResource::collection($this->invoiceItems),
            'additional_tax_info' => $this->additional_tax_info
        ];
    }
}
