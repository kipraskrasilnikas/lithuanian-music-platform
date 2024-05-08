<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserGenre>
 */
class UserGenreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $genres = config('music_config.genres');
        $randomGenre = $this->faker->randomElement($genres);

        return [
            'name' => $randomGenre,
        ];
    }
}
