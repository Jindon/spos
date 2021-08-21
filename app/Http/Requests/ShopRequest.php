<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'name' => ['required'],
            'address' => ['required'],
            'phone' => ['nullable', 'numeric'],
            'alt_phone' => ['nullable', 'numeric'],
            'email' => ['nullable', 'email'],
            'gstin' => ['nullable', 'max:15', 'min:15'],
            'bank_name' => ['nullable'],
            'bank_branch' => ['nullable'],
            'bank_ifsc' => ['nullable'],
            'bank_account' => ['nullable'],
        ];
    }
}
