<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Loan>
 */
class LoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 40),
            'loan_date' => $this->faker->date(),
            'amount' => $this->faker->randomFloat(2, 0, 1000),
            'installment_principal' => $this->faker->numberBetween(2, 0, 2000000),
            'installment_interest' => $this->faker->numberBetween(2, 0, 2000000),
            'total_installment' => $this->faker->numberBetween(2, 0, 2000000),
            'installment_remaining' => $this->faker->numberBetween(1, 10),
            'loan_type_id' => $this->faker->numberBetween(1, 10),
        ];
    }
}
