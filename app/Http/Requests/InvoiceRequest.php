<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InvoiceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $data = [
            'state_id' => ['nullable', 'exists:states,id'],
            'invoice_date' => ['required', 'date'],
            'walk_in_customer' => ['boolean'],
            'customer_name' => ['nullable'],
            'customer_address' => ['nullable'],
            'customer_gstin' => ['nullable'],
            'customer_pan' => ['nullable'],
            'items' => ['required', 'array'],
            'items.*.name' => ['required'],
            'items.*.quantity' => ['required'],
            'items.*.unit_price' => ['required'],
            'items.*.inclusive' => ['nullable', 'boolean'],
            'items.*.tax' => ['required'],
            'items.*.taxable_amount' => ['required'],
            'items.*.tax_amount' => ['required'],
            'items.*.total' => ['required'],
            'discount' => ['nullable']
        ];

        if ($this->method() == 'POST') {
            $data['invoice_no'] = [
                'required',
                // Rule::unique('invoices')->ignoreModel($this->invoice)
                Rule::unique('invoices')->where(function ($query) {
                    return $query->where('invoice_no', $this->invoice_no)
                    ->where('shop_id', auth()->user()->defaultShop()->id);
                }),
            ];
        }

        if ($this->method() == 'PATCH') {
            $data['invoice_no'] = [
                'required',
                // Rule::unique('invoices')->ignoreModel($this->invoice)
                Rule::unique('invoices')->where(function ($query) {
                    return $query->where('invoice_no', $this->invoice_no)
                    ->where('shop_id', auth()->user()->defaultShop()->id)
                    ->where('id', '><', $this->invoice->id);
                }),
            ];
        }

        return $data;
    }
}
