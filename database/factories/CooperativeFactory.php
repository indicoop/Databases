<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cooperative>
 */
class CooperativeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->unique()->numberBetween(1, 40),
            'name' => $this->faker->unique()->company(),
            'registration_number' => $this->faker->unique()->numberBetween(100000000, 999999999),
            'status' => $this->faker->boolean(),
            'effective_date' => $this->faker->date(),
            'status_grade' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E']),
            'date_of_establishment' => $this->faker->date(),
            'address' => $this->faker->address(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone_number' => $this->faker->phoneNumber(),
            'form_of_cooperative' => $this->faker->randomElement(['Koperasi Produksi', 'Koperasi Konsumsi', 'Koperasi Simpan Pinjam', 'Koperasi Investasi']),
            'certificate' => 'uploads/cooperative/certificate/default.png',
            'legal_entity_certificate' => 'uploads/cooperative/certificate/default.png',
            'profile_picture' => 'uploads/cooperative/profile_picture/default.png',
            'is_verified' => $this->faker->boolean(),
        ];
    }
}
