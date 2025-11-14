<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostCrudTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    /*public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }*/

    #[\PHPUnit\Framework\Attributes\Test]
    public function authenticated_user_can_create_a_post()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $postData = [
            'title' => 'Test Post',
            'content' => 'This is test content.',
        ];

        $response = $this->post('/posts', $postData);

        $response->assertRedirect(); // Resource controller redirects to index or show
        $this->assertDatabaseHas('posts', $postData);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function authenticated_user_can_update_a_post()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $post = Post::factory()->create();

        $updateData = [
            'title' => 'Updated Title',
            'content' => 'Updated Content',
        ];

        $response = $this->put('/posts/' . $post->id, $updateData);

        $response->assertRedirect();
        $this->assertDatabaseHas('posts', $updateData);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function authenticated_user_can_delete_a_post()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $post = Post::factory()->create();

        $response = $this->delete('/posts/' . $post->id);

        $response->assertRedirect();
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function guest_cannot_access_post_routes()
    {
        $response = $this->get('/posts');
        $response->assertRedirect('/login');
    }

}
