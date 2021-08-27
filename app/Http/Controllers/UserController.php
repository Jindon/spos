<?php

namespace App\Http\Controllers;

use App\Http\Resources\SettingResource;
use App\Models\Setting;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __invoke(Request $request)
    {
        $shop = $request->user()->defaultShop();
        $settings = Setting::where('shop_id', $shop->id)->first();
        $shop->load('state');
        return response()->json([
            'user' => $request->user(),
            'shop' => $shop,
            'settings' => $settings ? new SettingResource($settings) : null
        ]);
    }
}
