<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __invoke(Request $request)
    {
        $shop = $request->user()->defaultShop();
        $shop->load('state');
        return response()->json([
            'user' => $request->user(),
            'shop' => $shop
        ]);
    }
}
