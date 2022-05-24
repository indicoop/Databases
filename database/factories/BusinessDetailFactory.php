<?php

namespace Database\Factories;

use App\Models\Cooperative;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BusinessDetail>
 */
class BusinessDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'cooperative_id' => $this->faker->numberBetween(1, Cooperative::count()),
            'business_id' => $this->faker->numberBetween(1, 7),
        ];
    }
}
