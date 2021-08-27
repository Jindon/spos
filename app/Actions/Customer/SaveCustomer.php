<?php

namespace App\Actions\Customer;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class SaveCustomer
{
    public function handle(array $attributes, Customer $updateCustomer = null)
    {
        if ($updateCustomer) {
            $customer = $updateCustomer;
            $updateCustomer->update($attributes);
        } else {
            $attributes['shop_id'] = auth()->user()->defaultShop()->id;
            $customer = Customer::create($attributes);
        }

        $customer->fresh();
        return $customer;
    }
}
