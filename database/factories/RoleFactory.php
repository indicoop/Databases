<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->randomElement(['admin', 'cooperative_chairman', 'member', 'guest', 'secretary', 'treasurer', 'vice_chairman']),
        ];

        // Role::create([
        //     ['name' => 'Admin'],
        //     ['name' => 'Cooperative Chairman'],
        //     ['name' => 'Member'],
        //     ['name' => 'Guest'],
        //     ['name' => 'Secretary'],
        //     ['name' => 'Treasurer'],
        //     ['name' => 'Vice'],
        // ]);

    }
}
