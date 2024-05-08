<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use Database\Factories\UserFactory; // Add this import statement

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use Mockery;

class AuthManagerTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    public function test_user_can_view_a_login_form()
    {
        $response = $this->get('/login');

        $response->assertSuccessful();
        $response->assertViewIs('login');
    }

    public function test_user_cannot_view_a_login_form_when_authenticated()
    {
        // $user = factory(User::class)->make();
        $user = UserFactory::new()->make();

        $response = $this->actingAs($user)->get('/login');

        $response->assertRedirect('');
    }

    public function test_user_can_view_a_registration_form() {
        $response = $this->get('/registration');

        $response->assertSuccessful();
        $response->assertViewIs('registration');
    }

    public function test_user_cannot_view_a_registration_form_when_authenticated()
    {
        // $user = factory(User::class)->make();
        $user = UserFactory::new()->make();

        $response = $this->actingAs($user)->get('/registration');

        $response->assertRedirect('');
    }

    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'), // Ensure password is hashed
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_empty_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'), // Ensure password is hashed
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'invalidpassword',
        ]);

        $response->assertRedirect('/login')
            ->assertSessionHasInput('email') // Check if old input data is flashed
            ->assertSessionHas('error'); // Check if error message is flashed

        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertGuest();
    }

    public function test_user_can_register_successfully()
    {
        $response = $this->post('/registration', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response->assertRedirect(route('login'));

        $response->assertSessionHas('success', 'Registraciją sėkminga! Prisijunkite, kad pasiekti platformą.');
    }

    public function test_user_logout() {
        // Log in a user
        $user = UserFactory::new()->make();
        Auth::login($user);

        // Call the logout route
        $response = $this->get(route('logout'));

        // Assert that the user is redirected to the home page after logout
        $response->assertRedirect(route('home'));

        // Assert that the user is logged out
        $this->assertGuest();
    }
}
