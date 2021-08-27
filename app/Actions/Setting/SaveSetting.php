<?php

namespace App\Actions\Setting;

use App\Models\Setting;

class SaveSetting
{
    public function handle(array $attributes, int $shopId)
    {
        $setting = Setting::updateOrCreate([
            'shop_id' => $shopId,
        ], [
            'save_customers' => $attributes['save_customers'],
            'show_bank_details' => $attributes['show_bank_details']
        ]);

        return $setting;
    }
}
