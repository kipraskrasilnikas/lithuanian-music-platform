<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Illuminate\Support\Facades\Auth;

use App\Models\Resource;
use App\Models\User;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ResourceControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_resource_page_loads_correctly()
    {
        // Create a user (assuming you have a user)
        $user = User::factory()->create();

        // Create some resources
        $resources = Resource::factory()->count(10)->create();

        // Acting as the authenticated user
        $this->actingAs($user);

        // Paginate the resources
        $paginatedResources = Resource::paginate(10);

        // Hit the index route
        $response = $this->get('/resources');

        // Check response status
        $response->assertStatus(200);
        
        // Check if the authenticated user is passed to the view
        $response->assertViewHas('user', $user);

        // Check for specific content in the response
        foreach ($resources as $resource) {
            $response->assertSee($resource->name);
        }
    }

    /** @test */
    public function test_show_create_form()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/resources/create');

        $response->assertStatus(200)
                 ->assertViewIs('resources.create');
    }

    /** @test */
    public function test_store_resource()
    {
        Storage::fake('public'); // Use fake disk for file uploads
        $file = UploadedFile::fake()->image('avatar.jpg');

        // Store the file using the Storage facade
        Storage::putFileAs('public/avatars', $file, $file->hashName());

        $user = User::factory()->create();
        $this->actingAs($user);

        $data = [
            'name' => 'Resource Name',
            'type' => 'Resource Type',
            'description' => 'Resource Description',
            'image' => $file,
            'address' => 'Resource Address',
            'telephone' => 'Resource Telephone',
            'email' => 'resource@example.com',
            'county' => 'Resource County'
        ];

        $this->post('/resources', $data)
            ->assertRedirect('/resources')
            ->assertSessionHas('flash_message', 'Resursas įkeltas sėkmingai!');

        $this->assertDatabaseHas('resources', [
            'name' => $data['name'],
            'type' => $data['type'],
            'description' => $data['description'],
            'address' => $data['address'],
            'telephone' => $data['telephone'],
            'email' => $data['email'],
            'county' => $data['county'],
            'user_id' => $user->id,
        ]);

        $this->assertFileExists(storage_path('app/public/avatars/' . $file->hashName()));
    }

    public function test_show_fetches_resource_correctly()
    {
        // Create a resource instance
        $resource = Resource::factory()->create();

        // Send a GET request to the show route with the resource ID
        $response = $this->get('/resource/' . $resource->id);

        // Assert that the response status is 200 (OK)
        $response->assertStatus(200);

        // Assert that the returned view is resources.show
        $response->assertViewIs('resources.show');

        // Assert that the resource data is passed to the view
        $response->assertViewHas('resources', $resource);
    }

    public function test_edit_method_redirects_if_user_not_authorized()
    {
        // Create a resource instance
        $resource = Resource::factory()->create();

        // Attempt to access the edit route without authorization
        $response = $this->get('/resource/' . $resource->id . '/edit');

        // Assert that the response status is a redirect
        $response->assertRedirect();

        // Assert that a flash message is set
        $response->assertSessionHas('flash_message');

        // Assert that the flash message indicates lack of authorization
        $response->assertSessionHas('flash_type', 'danger');
    }

    public function test_update_method_updates_resource_successfully()
    {
        // Create a resource instance
        $resource = Resource::factory()->create();

        Storage::fake('public'); // Use fake disk for file uploads
        $file = UploadedFile::fake()->image('avatar.jpg');

        // Store the file using the Storage facade
        Storage::putFileAs('public/avatars', $file, $file->hashName());

        // Make a PUT request to update the resource with new data
        $response = $this->post('/resource/' . $resource->id, [
            'name' => 'Updated Name',
            'type' => 'Updated Type',
            'description' => 'Updated Description',
            'image' => $file,
            'address' => 'Updated Address',
            'telephone' => 'Updated Telephone',
            'email' => 'updated@example.com',
            'county' => 'Updated County'
        ]);

        // Assert that the update request was successful
        $response->assertRedirect(route('resources.show', $resource->id));

        // Assert that the resource details have been updated in the database
        $this->assertDatabaseHas('resources', [
            'id' => $resource->id,
            'name' => 'Updated Name',
            'type' => 'Updated Type',
            'description' => 'Updated Description',
            'address' => 'Updated Address',
            'telephone' => 'Updated Telephone',
            'email' => 'updated@example.com',
            'county' => 'Updated County',
        ]);

        // Assert that the new image file exists in the public/images directory
        // $this->assertFileExists(public_path('images/new_image.jpg'));
        $this->assertFileExists(storage_path('app/public/avatars/' . $file->hashName()));
    }

    public function test_destroy_method_deletes_resource_successfully()
    {
        // Create a user who will create the resource
        $user = User::factory()->create();

        // Create a resource instance and associate it with the user
        $resource = Resource::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        // Make a DELETE request to delete the resource
        $response = $this->delete('/resource/' . $resource->id);

        // Assert that the delete request was successful
        $response->assertRedirect(route('resources'));

        // Assert that the resource has been deleted from the database
        $this->assertDatabaseMissing('resources', ['id' => $resource->id]);

        // Assert that the flash message indicates successful deletion
        $response->assertSessionHas('flash_message', 'Resursas ištrintas sėkmingai!');
    }

    public function test_destroy_method_redirects_non_authorised_user()
    {
        // Create a resource instance
        $resource = Resource::factory()->create();

        // Simulate an unauthorised user
        $unauthorisedUser = User::factory()->create();

        // Log in as the unauthorised user
        $this->actingAs($unauthorisedUser);

        // Make a DELETE request to delete the resource
        $response = $this->delete('/resource/' . $resource->id);

        // Assert that the delete request was redirected
        $response->assertRedirect(route('resources'));

        // Assert that the flash message indicates lack of authorisation
        $response->assertSessionHas('flash_message', 'Trinti gali tik resurso autorius ar administratorius. Jei tai jūsų resursas, prisijunkite.');
    }
}
