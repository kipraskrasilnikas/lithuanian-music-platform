<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\UserGenre;
use App\Models\UserMood;
use App\Models\UserSpecialty;
use App\Models\UserLocation;
use App\Models\Resource;
use App\Models\Song;
use App\Models\SongMood;
use App\Models\SongGenre;

use Illuminate\Support\Arr;

class SearchControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic feature test example.
     */
    public function test_search_method_filters_current_user_and_inactive_users_and_orders_by_id_descending()
    {
        // Create a current user
        $currentUser = User::factory()->create();

        // Authenticate the current user
        $this->actingAs($currentUser);

        // Create some other users, some active and some inactive
        $activeUser = User::factory()->create(['status' => 1]);
        $inactiveUser = User::factory()->create(['status' => 0]);

        // Invoke the search method
        $response = $this->get('/search');

        // Assert that the response status is OK
        $response->assertOk();

        // Assert that the view is returned
        $response->assertViewIs('search');

        // Assert that the view contains the filtered and sorted users
        $response->assertViewHas('users', function ($users) use ($currentUser, $activeUser, $inactiveUser) {
            return $users->contains($activeUser) &&
                   !$users->contains($currentUser) &&
                   !$users->contains($inactiveUser) &&
                   $users->first()->id > $users->last()->id;
        });
    }

    public function test_searchPost_method_filters_users_based_on_search_criteria()
    {
        // Create a current user
        $currentUser = User::factory()->create();

        // Get sample data from music_config
        $genres = array_slice(config('music_config.genres'), 0, rand(3, 5));
        $specialties = array_slice(config('music_config.specialties'), 0, rand(3, 5));
        $counties = array_slice(config('music_config.counties'), 0, rand(3, 5));
        $moodCategories = config('music_config.music_moods');
        $moods = [];
        foreach ($moodCategories as $category) {
            $moods = array_merge($moods, array_slice($category['moods'], 0, rand(3, 5)));
        }

        // Create other users with various attributes
        $matchingUser = User::factory()->create([
            'name' => 'Thorfinn',
            'status' => 1,
        ]);

        foreach ($genres as $genre) {
            UserGenre::factory()->create([
                'user_id' => $matchingUser->id,
                'name' => $genre,
            ]);
        }

        foreach ($moods as $mood) {
            UserMood::factory()->create([
                'user_id' => $matchingUser->id,
                'mood' => $mood,
            ]);
        }

        foreach ($counties as $county) {
            UserLocation::factory()->create([
                'user_id' => $matchingUser->id,
                'county' => $county,
                'city' => 'City', // Assuming you have a city field in the UserLocation model
            ]);
        }

        foreach ($specialties as $specialty) {
            UserSpecialty::factory()->create([
                'user_id' => $matchingUser->id,
                'name' => $specialty,
            ]);
        }

        $nonMatchingUser = User::factory()->create([
            'name' => 'Oars',
            'status' => 1,
        ]);

        $response = $this->get('/searchPost?' . http_build_query([
            'search'        => 'Thorfinn',
            'genres'        => $genres,
            'specialties'   => $specialties,
            'counties'      => $counties,
            'moods'         => $moods,
        ]));

        // Assert that the response status is OK
        $response->assertOk();

        // Assert that the view is returned
        $response->assertViewIs('search');

        // Assert that the view contains the matching user and does not contain the non-matching user
        $response->assertViewHas('users', function ($users) use ($matchingUser, $nonMatchingUser, $currentUser) {
            return $users->contains($matchingUser) &&
                   !$users->contains($nonMatchingUser) &&
                   !$users->contains($currentUser);
        });
    }

    public function test_searchResource_method_filters_resources_based_on_search_criteria()
    {
        // Create a user
        $user = User::factory()->create();

        // Flatten the nested array of counties
        $counties = Arr::flatten(config('music_config.counties'));
        $county = Arr::random($counties);

        $types = Arr::flatten(config('music_config.resource_types'));
        $type = Arr::random($types);

        // Create sample resources
        $matchingResource = Resource::factory()->create([
            'name' => 'Studija Vilniuje',
            'description' => 'Resource description',
            'county' => $county,
            'type' => $type,
        ]);

        $nonMatchingResource = Resource::factory()->create([
            'name' => 'Non-Matching Resource',
        ]);
    
        // Mock the request with search criteria
        $response = $this->get('/searchResource?' . http_build_query([
            'search' => 'Studija',
            'counties' => [$county],
            'types' => [$type],
        ]));

        // Assert that the response status is OK
        $response->assertOk();

        // Assert that the view is returned
        $response->assertViewIs('resources.index');

        // Assert that the view contains the matching resource and does not contain the non-matching resource
        $response->assertViewHas('resources', function ($resources) use ($matchingResource, $nonMatchingResource) {
            return $resources->contains($matchingResource) &&
                !$resources->contains($nonMatchingResource);
        });
    }

    public function test_searchMusic_method_filters_songs_based_on_search_criteria()
    {
        // Create sample songs
        $matchingSong = Song::factory()->create([
            'title' => 'Infinity',
        ]);
        
        $nonMatchingSong = Song::factory()->create([
            'title' => 'All-Star',
        ]);

        // blemba bet vienu callu reiktu cia viska

        // Get random moods associated with the matching song
        $matchingSongMoods = $matchingSong->moods()->pluck('mood')->toArray();

        // Get random genres associated with the matching song
        $matchingSongGenres = $matchingSong->genres()->pluck('genre')->toArray();

        // Mock the request with search criteria
        $response = $this->get('/searchMusic?' . http_build_query([
            'search' => 'Infinity',
            'moods' => $matchingSongMoods,
            'genres' => $matchingSongGenres,
        ]));

        // Assert that the response status is OK
        $response->assertOk();

        // Assert that the view is returned
        $response->assertViewIs('music');

        // Assert that the view contains the matching song and does not contain the non-matching song
        $response->assertViewHas('songs', function ($songs) use ($matchingSong, $nonMatchingSong) {
            return $songs->contains($matchingSong) &&
                !$songs->contains($nonMatchingSong);
        });
    }

    public function test_searchMusic_method_filters_users_based_on_search_criteria()
    {
        $matchingUser = User::factory()->create([
            'name'  => 'Gokas'
        ]);

        $nonMatchingUser = User::factory()->create([
            'name'  => 'Son Gokas',
        ]);

        $userMoods = ['Liūdna', 'Atsipūtusi'];
        $userGenres = ['Choras', 'Elektroninė šokių muzika', 'Metalas'];

        foreach ($userMoods as $mood) {
            UserMood::factory()->create([
                'mood'  => $mood,
                'user_id'   => $matchingUser->id
            ]);
        }

        foreach ($userGenres as $genre) {
            UserGenre::factory()->create([
                'name'  => $genre,
                'user_id'   => $matchingUser->id
            ]);
        }

        // Mock the request with search criteria
        $response = $this->get('/searchMusic?' . http_build_query([
            'search' => 'Gokas',
            'moods' => $userMoods,
            'genres' => $userGenres
        ]));

        // Assert that the response status is OK
        $response->assertOk();

        // Assert that the view is returned
        $response->assertViewIs('music');

        // Assert that the view contains the matching song and does not contain the non-matching song
        $response->assertViewHas('users', function ($users) use ($matchingUser, $nonMatchingUser) {
            return $users->contains($matchingUser) &&
                !$users->contains($nonMatchingUser);
        });
    }

    public function test_searchSongs_method()
    {
        // Create sample songs with associated moods and genres
        $song1 = Song::factory()->create(['title' => 'Song 1']);
        $song2 = Song::factory()->create(['title' => 'Song 2']);

        // Associate moods and genres with the sample songs
        $moodsSong1 = ['Klajojanti', 'Besikeičiančio tempo'];
        $genresSong1 = ['Repas ir Hip-hopas', 'Ritmenbliuzas'];
        foreach ($moodsSong1 as $mood) {
            SongMood::factory()->create(['song_id' => $song1->id, 'mood' => $mood]);
        }
        foreach ($genresSong1 as $genre) {
            SongGenre::factory()->create(['song_id' => $song1->id, 'genre' => $genre]);
        }

        $moodsSong2 = ['Taikinga/rami', 'Atsipūtusi'];
        $genresSong2 = ['Choras', 'Metalas'];
        foreach ($moodsSong2 as $mood) {
            SongMood::factory()->create(['song_id' => $song2->id, 'mood' => $mood]);
        }
        foreach ($genresSong2 as $genre) {
            SongGenre::factory()->create(['song_id' => $song2->id, 'genre' => $genre]);
        }

        // Mock the request with search criteria
        $response = $this->get('/searchSongs?' . http_build_query([
            'search' => 'Song',
            'moods' => ['Klajojanti'],
            'genres' => ['Choras']
        ]));

        // Assert that the response status is OK
        $response->assertOk();

        // Assert that the view is returned
        $response->assertViewIs('music.songs');

        // Assert that the view contains the correct songs based on the search criteria
        $response->assertViewHas('songs', function ($songs) use ($song1, $song2) {
            return $songs->contains($song1) && $songs->contains($song2);
        });

        // Assert that the view contains the search criteria
        $response->assertViewHas('search', 'Song');
        $response->assertViewHas('search_moods', ['Klajojanti']);
        $response->assertViewHas('search_genres', ['Choras']);
    }

    public function test_searchArtists_method()
    {
        // Create sample users with associated moods and genres
        $user1 = User::factory()->create(['name' => 'User 1']);
        $user2 = User::factory()->create(['name' => 'User 2']);

        // Associate moods and genres with the sample users
        $moodsUser1 = ['Laiminga', 'Viltinga'];
        $genresUser1 = ['Popmuzika', 'Garso takelis'];
        foreach ($moodsUser1 as $mood) {
            UserMood::factory()->create(['user_id' => $user1->id, 'mood' => $mood]);
        }

        foreach ($genresUser1 as $genre) {
            UserGenre::factory()->create(['user_id' => $user1->id, 'name' => $genre]);
        }

        $moodsUser2 = ['Tamsi', 'Baiminga'];
        $genresUser2 = ['Indie', 'Alternatyva'];
        foreach ($moodsUser2 as $mood) {
            UserMood::factory()->create(['user_id' => $user2->id, 'mood' => $mood]);
        }
        foreach ($genresUser2 as $genre) {
            UserGenre::factory()->create(['user_id' => $user2->id, 'name' => $genre]);
        }

        // Mock the request with search criteria
        $response = $this->get('/searchArtists?' . http_build_query([
            'search' => 'User',
            'moods' => ['Laiminga'],
            'genres' => ['Popmuzika']
        ]));

        // Assert that the response status is OK
        $response->assertOk();

        // Assert that the view is returned
        $response->assertViewIs('music.artists');

        // Assert that the view contains the correct users based on the search criteria
        $response->assertViewHas('users', function ($users) use ($user1, $user2) {
            return $users->contains($user1) && !$users->contains($user2);
        });

        // Assert that the view contains the search criteria
        $response->assertViewHas('search', 'User');
        $response->assertViewHas('search_moods', ['Laiminga']);
        $response->assertViewHas('search_genres', ['Popmuzika']);
    }

}