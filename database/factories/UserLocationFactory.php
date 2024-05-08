<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\SongMood;
use App\Models\SongGenre;

use Illuminate\Support\Arr;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserLocation>
 */
class UserLocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get the counties configuration
        $countiesConfig = config('music_config.counties');

        // Flatten the nested array of counties
        $counties = Arr::flatten($countiesConfig);

        // Select one random county
        $randomCounty = Arr::random($counties);

        return [
            'county'    => $randomCounty, // You may adjust this as needed
            'city'      => $randomCounty . ' city',
            'address'   => $this->faker->address,
        ];
    }
}
