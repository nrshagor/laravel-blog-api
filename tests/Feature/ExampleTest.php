<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    public function test_post_creation()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $postData = [
            'title' => 'Sample Title',
            'body' => 'Sample content of the post.',
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response->assertStatus(201)
            ->assertJson(['data' => ['title' => 'Sample Title']]);

        $this->assertDatabaseHas('posts', ['title' => 'Sample Title']);
    }
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $blogPostApi = $this->get('api/posts');

        $blogPostApi->assertStatus(200);
    }
}
