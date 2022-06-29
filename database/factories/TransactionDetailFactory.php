<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransactionDetail>
 */
class TransactionDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'transaction_id' => Transaction::factory()->create()->id,
            'user_id' => $this->faker->numberBetween(1, 10),
            'courier_id' => $this->faker->numberBetween(1, 10),
            'cooperative_id' => $this->faker->numberBetween(1, 10),
            'total_pay' => $this->faker->numberBetween(50000, 100000),
            'payment_method_id' => $this->faker->numberBetween(1, 5),
            'status' => $this->faker->randomElement(['pending', 'success', 'cancel']),
            'shipping_fee' => $this->faker->numberBetween(5000, 10000),
        ];
    }
}
