<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required'],
            'address' => ['nullable'],
            'state_id' => ['required', 'exists:states,id'],
            'gstin' => ['nullable'],
            'pan' => ['nullable'],
        ];
    }
}
