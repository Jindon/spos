<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Shop;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'shop_id' => 1,
            'state_id' => 14,
            'name' => $this->faker->name,
            'address' => $this->faker->address,
            'gstin' => '18AABCU9603R1ZM',
            'pan' => 'BAJPC4350M',
            'type' => null,
        ];
    }
}
