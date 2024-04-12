<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Resource;
use Faker\Factory as Faker;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the number of resources you want to create
        $numberOfResources = 10;

        // Get the resource types from the config
        $resourceTypes = config('music_config.resource_types');

        // Get the counties from the config
        $counties = config('music_config.counties');

        // Create Faker instance
        $faker = Faker::create('lt_LT');

        // Loop to create resources
        for ($i = 0; $i < $numberOfResources; $i++) {
            $randomType = $resourceTypes[array_rand($resourceTypes)];
            $randomCounty = $counties[array_rand($counties)];

            // Generate a semi-realistic name
            $name = ucfirst($faker->unique()->words(rand(1, 3), true));

            // Create a new resource
            $resource = new Resource([
                'name'          => $name,
                'rating'        => rand(1, 5), // Random rating between 1 and 5
                'type'          => $randomType,
                'description'   => 'Įdomus muzikos išteklius, praturtintas skirtingų stilių, talentų ir idėjų, kviečia į paslaptingą muzikinį pasaulį. Šis unikalus šaltinis suteikia platų muzikinės kūrybos spektrą, leidžiantį keliauti į įvairias garso ir ritmo dimensijas. Čia susitinka įvairios muzikos formos ir kūrybiniai metodai, kurie skatina atradimus ir kūrybinę paiešką. Šio resurso garsai skamba įvairiuose žanruose ir stiliuose, atverdami duris į įvairiapusį muzikinį kraštovaizdį. Pasinėrimas į šį muzikinį šaltinį žada įkvepiančią kelionę, kurioje kiekvienas gali atrasti naują muzikos pasaulio kampelį.',
                'image'         => 'image_url_' . ($i + 1) . '.jpg', // Replace with actual image URLs
                'address'       => $faker->streetAddress,
                'county'        => $randomCounty
            ]);

            // Generate random email
            $email = $faker->unique()->safeEmail;

            // Generate random Lithuanian phone number
            $telephone = '+3706' . $faker->numberBetween(1000000, 9999999);

            // Get a random user to assign as the owner of the resource
            $user = User::inRandomOrder()->first();

            // Associate the resource with the user
            $resource->user()->associate($user);

            // Set the email and phone number
            $resource->email = $email;
            $resource->telephone = $telephone;

            // Save the resource
            $resource->save();
        }
    }
}
