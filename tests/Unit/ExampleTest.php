<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase; // Import Laravel's TestCase

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_creation_validation_errors()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/posts', []);

        $response->assertStatus(422) // Expect validation errors status code
            ->assertJsonValidationErrors(['title', 'body']); // Check for validation errors on 'title' and 'body'
    }

    public function test_post_creation_unauthorized()
    {
        $postData = [
            'title' => 'Sample Title',
            'body' => 'Sample content of the post.',
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response->assertStatus(401); // Unauthorized status code
    }



    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }
}
