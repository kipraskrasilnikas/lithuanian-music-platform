<?php

namespace Database\Factories;

use App\Models\SongMood;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SongMood>
 */
class SongMoodFactory extends Factory
{
    protected $model = SongMood::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'mood' => $this->faker->word(), // You may adjust this as needed
        ];
    }
}
