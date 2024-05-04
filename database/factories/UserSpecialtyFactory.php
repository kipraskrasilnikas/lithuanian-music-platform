<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserSpecialty>
 */
class UserSpecialtyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $specialties = config('music_config.specialties');
        $randomSpecialty = $this->faker->randomElement($specialties);

        return [
            'name' => $randomSpecialty,
        ];
    }
}
