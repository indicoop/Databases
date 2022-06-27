<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'product_id' => $this->faker->numberBetween(1, 10),
            'quantity' => $this->faker->numberBetween(1, 10),
            'destination_address' => $this->faker->address,
            'voucher_id' => $this->faker->numberBetween(1, 10),
            'note' => $this->faker->text,
        ];
    }
}
