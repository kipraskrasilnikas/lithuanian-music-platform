<?php

namespace Database\Factories;

use App\Models\SongGenre;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SongGenre>
 */
class SongGenreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'genre' => $this->faker->word(), // You may adjust this as needed
        ];
    }
}
