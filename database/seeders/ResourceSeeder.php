<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Resource;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the number of resources you want to create
        $numberOfResources = 10;

        // Loop to create resources
        for ($i = 0; $i < $numberOfResources; $i++) {
            // Create a new resource
            $resource = new Resource([
                'name' => 'Resource ' . ($i + 1),
                'rating' => rand(1, 5), // Random rating between 1 and 5
                'type' => 'Type ' . ($i + 1),
                'description' => 'Description for Resource ' . ($i + 1),
                'image' => 'image_url_' . ($i + 1) . '.jpg', // Replace with actual image URLs
                'address' => 'Address for Resource ' . ($i + 1),
            ]);

            // Get a random user to assign as the owner of the resource
            $user = User::inRandomOrder()->first();

            // Associate the resource with the user
            $resource->user()->associate($user);

            // Save the resource
            $resource->save();
        }
    }
}
