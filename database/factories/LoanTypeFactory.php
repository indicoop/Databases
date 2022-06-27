<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LoanType>
 */
class LoanTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'type' => $this->faker->word,
            'period' => $this->faker->date(),
            'interest' => $this->faker->randomFloat(2, 0, 10),
            'fine' => $this->faker->randomFloat(2, 0, 10),
        ];
    }
}
