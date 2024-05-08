<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserMood>
 */
class UserMoodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $moods = [];
        foreach (config('music_config.music_moods') as $moodGroup) {
            $moods = array_merge($moods, $moodGroup['moods']);
        }
        $randomMood = $this->faker->randomElement($moods);

        return [
            'mood' => $randomMood,
        ];    
    }
}
