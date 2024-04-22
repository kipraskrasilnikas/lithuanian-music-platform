<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Song;
use App\Models\SongGenre;
use App\Models\SongMood;
use App\Models\User;

class SongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $songs = [
            [
                'title' => "Someone Like You",
                'youtube_id' => "hLQl3WQQoQ0",
            ],
            [
                'title' => "Hello",
                'youtube_id' => "YQHsXMglC9A",
            ],
            [
                'title' => "Counting Stars",
                'youtube_id' => "hT_nvWreIhg",
            ],
            [
                'title' => "Closer",
                'youtube_id' => "PT2_F-1esPk",
            ],
            [
                'title' => "We Don't Talk Anymore",
                'youtube_id' => "3AtDnEC4zak",
            ],
            [
                'title' => "Love Yourself",
                'youtube_id' => "oyEuk8j8imI",
            ],
            [
                'title' => "Havana",
                'youtube_id' => "BQ0mxQXmLsk",
            ],
            [
                'title' => "Bad Guy",
                'youtube_id' => "DyDfgMOUjCI",
            ],
            [
                'title' => "Sicko Mode",
                'youtube_id' => "6ONRf7h3Mdk",
            ],
            [
                'title' => "Old Town Road",
                'youtube_id' => "w2Ov5jzm3j8",
            ],
        ];

        // Get a random number of users (between 1 and 5)
        $numUsers = rand(1, 5);

        // Retrieve random users from the database
        $users = User::inRandomOrder()->take($numUsers)->get();

        foreach ($users as $user) {
            // Determine the number of songs to associate with the user (between 1 and 5)
            $numSongs = rand(1, 5);

            // Shuffle the songs array to randomize the selection
            shuffle($songs);

            // Take a slice of the songs array based on the determined number of songs
            $selectedSongs = array_slice($songs, 0, $numSongs);
        
            // Loop through each popular song
            foreach ($selectedSongs as $songData) {
                // Create a new song instance
                $song = new Song([
                    'title' => $songData['title'],
                    'original_url' => 'https://www.youtube.com/watch?v=' . $songData['youtube_id'],
                    'embedded_url' => 'https://www.youtube.com/embed/' . $songData['youtube_id'],
                ]);

                // Assign a random user to the song
                $user = User::inRandomOrder()->first(); // Get a random user
                $song->user()->associate($user);
                $song->save();

                $this->assignGenresAndMoods($song);
            }
        }
    }

    private function assignGenresAndMoods($song)
    {
        $genres = config('music_config.genres');
        $moods = config('music_config.music_moods');

        shuffle($genres);
        $genresCount = rand(1, min(count($genres), 3));
        $uniqueGenres = array_slice($genres, 0, $genresCount); // Get a slice of unique genres

        foreach ($uniqueGenres as $genre) {
            $song->genres()->create([
                'genre' => $genre,
            ]);
        }

        foreach ($moods as $mood) {
            $categoryMoods = $mood['moods'];
            shuffle($categoryMoods);

            $moodCount = rand(0, min(count($categoryMoods), 2)); // Random number of moods between 1 and 5
            $uniqueMoods = array_slice($categoryMoods, 0, $moodCount); // Get a slice of unique moods

            foreach ($uniqueMoods as $mood) {
                $song->moods()->create([
                    'mood' => $mood,
                ]);
            }
        }
    }
}
