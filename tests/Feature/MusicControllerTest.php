<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Song;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MusicControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_music_page_loads_correctly(): void
    {
        $users = User::factory()->count(2)->create();
        // Create some sample songs and users in the database
        $songs = Song::factory()->count(3)->create();

        // Hit the /music route
        $response = $this->get('/music');

        // Assert that the response is successful
        $response->assertStatus(200);
        
        // Assert that the view contains the songs and users
        foreach ($songs as $song) {
            $response->assertSee($song->title);

            foreach ($song->genres as $genre) {
                $response->assertSee($genre['genre']);
            }
            foreach ($song->moods as $mood) {
                $response->assertSee($mood['mood']);
            }
        }
        foreach ($users as $user) {
            $response->assertSee($user->name);
        }
    }
}
