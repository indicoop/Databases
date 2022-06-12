<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'business_detail_id' => $this->faker->numberBetween(1, 10),
            'product_category_id' => $this->faker->numberBetween(1, 10),
            'name' => $this->faker->name,
            'price' => $this->faker->randomFloat(2, 0, 1000000),
            'stock' => $this->faker->numberBetween(1, 100),
            'description' => $this->faker->text,
            'thumbnail' => $this->faker->imageUrl(400, 400),
            'production_date' => $this->faker->date('Y-m-d'),
            'discount' => $this->faker->randomFloat(2, 0, 100),
            'weight' => $this->faker->randomFloat(2, 0, 100),
            'voucher_id' => $this->faker->numberBetween(1, 10),
        ];
    }
}
