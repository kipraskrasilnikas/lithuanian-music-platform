<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = ['Laurynas', 'Kipras', 'Aiste', 'Jonas', 'Ruta', 'Karolina', 'Marius', 'Austeja', 'Mantas', 'Egle'];
        
        $genres = config('music_config.genres');
        $specialties = config('music_config.specialties');
        $counties = config('music_config.counties');
        $moods = config('music_config.music_moods');

        foreach($names as $name) {
            $user = $this->createUser($name);
            $this->createLocations($user, $counties);
            $this->createGenres($user, $genres);
            $this->createSpecialties($user, $specialties);
            $this->createMoods($user, $moods);
        }
    }

    private function createUser($name)
    {
        $password = $name . '123'; // Password is the name plus '123'

        return DB::table('users')->insertGetId([
            'name'                  => $name,
            'email'                 => strtolower($name) . '_' . substr(Str::uuid(), 5) . '@example.com', // Generate email based on name
            'email_verified_at'     => now(),
            'description'           => 'Versatile session musician with 5 years of experience in studio recordings and live performances. Worked in a variety of styles and with diverse clients. Proficient in multiple specialties, whichever are applicable.',
            'status'                => 1,        
            'password'              => Hash::make($password),
            'remember_token'        => Str::random(10),
        ]);
    }

    private function createLocations($userId, $counties)
    {
        shuffle($counties); // Shuffle the counties array to randomize the selection

        $locationsCount = rand(1, min(count($counties), 3)); // Random number of counties between 1 and 3

        for ($i = 0; $i < $locationsCount; $i++) {
            $county = $counties[$i]; // Get the county at index $i
            DB::table('locations')->insert([
                'user_id' => $userId,
                'county' => $county,
                'city' => $county . ' City', // You can modify this based on your requirement
                'address' => rand(100, 999) . ' Example Street', // Generate a random address
            ]);
        }
    }

    private function createGenres($userId, $genres)
    {
        shuffle($genres); // Shuffle the genres array to randomize the selection

        $genresCount = rand(1, min(count($genres), 5)); // Random number of genres between 1 and 5

        $uniqueGenres = array_slice($genres, 0, $genresCount); // Get a slice of unique genres

        foreach ($uniqueGenres as $genre) {
            DB::table('genres')->insert([
                'user_id' => $userId,
                'name' => $genre,
            ]);
        }
    }

    private function createSpecialties($userId, $specialties)
    {
        shuffle($specialties); // Shuffle the specialties array to randomize the selection

        $specialtiesCount = rand(1, min(count($specialties), 5)); // Random number of specialties between 1 and 5

        $uniqueSpecialties = array_slice($specialties, 0, $specialtiesCount); // Get a slice of unique specialties

        foreach ($uniqueSpecialties as $specialty) {
            DB::table('specialties')->insert([
                'user_id' => $userId,
                'name' => $specialty,
            ]);
        }
    }
    private function createMoods($userId, $moods)
    {
        shuffle($moods);

        foreach ($moods as $mood) {
            $categoryMoods = $mood['moods'];
            shuffle($categoryMoods);

            $moodCount = rand(0, min(count($categoryMoods), 2)); // Random number of moods between 1 and 5

            $uniqueMoods = array_slice($categoryMoods, 0, $moodCount); // Get a slice of unique moods

            foreach ($uniqueMoods as $mood) {
                DB::table('artist_moods')->insert([
                    'user_id' => $userId,
                    'mood' => $mood,
                ]);
            }
        }
    }
}
