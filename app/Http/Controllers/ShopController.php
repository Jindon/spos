<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShopRequest;
use App\Http\Resources\ShopResource;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function show()
    {
        $defaultShop = auth()->user()->defaultShop();
        $defaultShop->load('state');
        return new ShopResource($defaultShop);
    }

    public function save(ShopRequest $request)
    {
        $data = $request->validated();
        $shop = auth()->user()->defaultShop();
        $shop->update($data);
        $shop->load('state');
        return $this->successResponse('Successfully saved!', $shop->fresh());
    }
}
