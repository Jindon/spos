<?php

namespace App\Http\Controllers;

use App\Actions\Product\SaveProduct;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller
{
    public function index()
    {
        $shopId = auth()->user()->defaultShop()->id;
        $invoices = QueryBuilder::for(Product::class)
            ->where('shop_id', $shopId)
            ->allowedFilters([
                AllowedFilter::scope('search'),
            ])
            ->latest()
            ->paginate(request('limit', 10));
        return ProductResource::collection($invoices);
    }

    public function show(Product $product)
    {
        $this->authorize('view', $product);
        return new ProductResource($product);
    }

    public function store(ProductRequest $request, SaveProduct $saveProduct)
    {
        $attributes = $request->validated();
        $product = $saveProduct->handle($attributes);
        return new ProductResource($product);
    }

    public function update(ProductRequest $request, Product $product, SaveProduct $saveProduct)
    {
        $this->authorize('update', $product);
        $attributes = $request->validated();
        $product = $saveProduct->handle($attributes, $product);
        return new ProductResource($product);
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        try {
            $product->delete();
            return response()->json([
                'message' => 'Product successfully deleted'
            ]);
        } catch (\Exception $error) {
            abort(400, $error->getMessage());
        }
    }
}
