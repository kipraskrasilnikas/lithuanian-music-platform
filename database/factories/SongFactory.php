<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Song;
use App\Models\User;
use App\Models\SongMood;
use App\Models\SongGenre;

use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Song>
 */
class SongFactory extends Factory
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

        
        return [
            'title' => fake()->name(),
            'user_id' => $userId, // Associate each song with a user
        ];
    }

    public function configure()
    {

        return $this->afterCreating(function (Song $song) {
            // Associate random moods with the song
            $musicMoods = config('music_config.music_moods');
            $selectedMoods = [];


            foreach ($musicMoods as $moodGroup) {
                $selectedMoods = array_merge($selectedMoods, $moodGroup['moods']);
            }

            $selectedMoods = Arr::flatten($selectedMoods);
            $moods = collect($selectedMoods)->random(random_int(3, 5));

            // butent save negerai sitas. 

            foreach ($moods as $mood) {
                $songMood = SongMood::factory()->create([
                    'mood' => $mood,
                    'song_id' => $song->id, // Associate the SongMood with the Song
                ]);
                $song->moods()->save($songMood);
            }

            // Associate random genres with the song
            $genres = collect(config('music_config.genres'))->random(random_int(3, 5));
            foreach ($genres as $genre) {
                $songGenre = SongGenre::factory()->create([
                    'genre' => $genre,
                    'song_id' => $song->id, // Associate the SongMood with the Song
                ]);
                $song->genres()->save($songGenre);
            }
        });
    }
}
