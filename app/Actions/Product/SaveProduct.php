<?php

namespace App\Actions\Product;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class SaveProduct
{
    public function handle(array $attributes, Product $updateProduct = null)
    {
        try {
            DB::beginTransaction();

            if ($updateProduct) {
                $product = $updateProduct;
                $updateProduct->update($attributes);
            } else {
                $attributes['shop_id'] = auth()->user()->defaultShop()->id;
                $product = Product::create($attributes);
            }
            DB::commit();

            $product->fresh();
            return $product;
        } catch (\Exception $error) {
            DB::rollBack();
            return $error;
        }
    }
}
