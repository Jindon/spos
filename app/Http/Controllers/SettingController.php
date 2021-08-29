<?php

namespace App\Http\Controllers;

use App\Actions\Setting\SaveSetting;
use App\Http\Requests\SettingRequest;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::where('shop_id', auth()->user()->defaultShop()->id)
            ->first();
        return $settings ? new SettingResource($settings) : null;
    }
    public function save(SettingRequest $request, SaveSetting $saveSetting)
    {
        $attributes = $request->validated();
        $shopId = auth()->user()->defaultShop()->id;
        $settings = $saveSetting->handle($attributes, $shopId);
        if ($settings) {
            return new SettingResource($settings);
        }
        return $this->failureResponse('Unable to save settings!', null, 400);
    }
}
