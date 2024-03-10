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
    
        // Repeat the insertion process for 10 times
        for ($i = 0; $i < 10; $i++) {
            $name = $names[array_rand($names)]; // Randomly select a name from the $names array
            $genre = $genres[array_rand($genres)]; // Randomly select a genre from the $genres array
            $specialty = $specialties[array_rand($specialties)]; // Randomly select a specialty from the $specialties array
            $password = $name . '123'; // Password is the name plus '123'

            $userId = DB::table('users')->insertGetId([
                'name' => $name,
                'genre' => $genre,
                'specialty' => $specialty,
                'email' => strtolower($name) . '_' . substr(Str::uuid(), 5) . '@example.com', // Generate email based on name
                'email_verified_at' => now(),
                'password' => Hash::make($password),
                'remember_token' => Str::random(10),
            ]);

            $county = $counties[array_rand($counties)]; // Randomly select a county from the $counties array

            DB::table('locations')->insert([
                'user_id' => $userId,
                'county' => $county,
                'city' => $county . ' City', // You can modify this based on your requirement
                'address' => rand(100, 999) . ' Example Street', // Generate a random address
            ]);
        }
    }
}
