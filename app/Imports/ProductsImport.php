<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImport implements ToModel, WithValidation, WithHeadingRow
{
    use Importable;

    public $shopId;

    public function __construct(int $shopId)
    {
        $this->shopId = $shopId;
    }

    public function model(array $row)
    {
        return new Product([
            'shop_id' => $this->shopId,
            'name' => $row['name'],
            'code' => $row['code'],
            'unit_price' => $row['unit_price'],
            'tax' => $row['tax'],
            'inclusive' => $row['tax_inclusive'],
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required'],
            'code' => ['nullable'],
            'unit_price' => ['required', 'numeric'],
            'tax' => ['required', 'numeric'],
            'tax_inclusive' => [
                'required',
                Rule::in([1,0])
            ],
        ];
    }
}
