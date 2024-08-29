<?php

namespace Tests\Feature;

use App\Models\Post;
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
            ->assertJson(['title' => 'Sample Title']);

        $this->assertDatabaseHas('posts', ['title' => 'Sample Title']);
    }

    public function test_post_update()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $updatedPostData = [
            'title' => 'Updated Title',
            'body' => 'Updated content of the post.',
        ];

        $response = $this->putJson("/api/posts/{$post->id}", $updatedPostData);

        $response->assertStatus(200)
            ->assertJson(['title' => 'Updated Title']);

        $this->assertDatabaseHas('posts', ['title' => 'Updated Title']);
    }

    public function test_post_deletion()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(204); // Expect 204 for successful deletion with no content
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_user_can_create_post_and_add_comment()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Create a post
        $postData = [
            'title' => 'Sample Title',
            'body' => 'Sample content of the post.',
        ];
        $postResponse = $this->post('/api/posts', $postData, ['Accept' => 'application/json']);

        $postResponse->assertStatus(201)
            ->assertJson(['title' => 'Sample Title']);

        $postId = $postResponse->json('id');

        // Add a comment
        $commentData = [
            'body' => 'Sample comment.',
        ];
        $commentResponse = $this->post("/api/posts/{$postId}/comments", $commentData, ['Accept' => 'application/json']);

        $commentResponse->assertStatus(201)
            ->assertJson([
                'body' => 'Sample comment.',
                'user_id' => $user->id, // Optional: Check if you need to validate user_id
                'post_id' => $postId,    // Optional: Check if you need to validate post_id
            ]);

        // Verify that the comment is added
        $this->assertDatabaseHas('comments', ['body' => 'Sample comment.']);
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
