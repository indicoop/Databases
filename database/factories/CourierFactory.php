<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Courier>
 */
class CourierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Courier 1',
                'Courier 2',
                'Courier 3',
                'Courier 4',
                'Courier 5',
                'Courier 6',
                'Courier 7',
                'Courier 8',
                'Courier 9',
                'Courier 10']),
            'phone_number' => $this->faker->phoneNumber,
        ];
    }
}
