<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'shop_id' => Shop::first()->id,
            'name' => $this->faker->word(),
            'code' => Str::random(6),
            'unit_price' => $this->faker->numberBetween(25, 130),
            'tax' => $this->faker->randomElement([5,12,18]),
            'inclusive' => $this->faker->boolean()
        ];
    }
}
