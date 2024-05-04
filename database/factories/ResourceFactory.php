<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resource>
 */
class ResourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get all user IDs from the database
        $userIds = User::pluck('id')->all();

        // Select a random user ID from the list
        $userId = $this->faker->randomElement($userIds);

        $resourceTypes = config('music_config.resource_types');
        $randomType = $resourceTypes[array_rand($resourceTypes)];

        return [
            'name' => $this->faker->company,
            'address' => $this->faker->address,
            'type' => $randomType,
            'description' => $this->faker->paragraph,
            'image' => $this->faker->imageUrl(),
            'email' => $this->faker->email,
            'telephone' => $this->faker->phoneNumber,
            'county' => $this->faker->randomElement(config('music_config.counties')),
            'user_id' => $userId, // Associate each song with a user
        ];
    }
}
