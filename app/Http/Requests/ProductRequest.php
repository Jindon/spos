<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required'],
            'code' => ['nullable'],
            'unit_price' => ['required', 'numeric'],
            'inclusive' => ['nullable', 'boolean'],
            'tax' => ['required', 'numeric'],
        ];
    }
}
