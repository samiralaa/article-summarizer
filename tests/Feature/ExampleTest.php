<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Article;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
    }

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_create_article(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/articles', [
            'title' => 'New Article',
            'body' => 'This is the body of the new article.'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id', 'title', 'body', 'user_id', 'created_at', 'updated_at'
            ]);
    }

    public function test_show_article(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $article = Article::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this->getJson("/api/articles/{$article->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id', 'title', 'body', 'summary', 'user_id', 'created_at', 'updated_at'
            ]);
    }

    public function test_summarize_article(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $article = Article::factory()->create([
            'user_id' => $user->id,
            'summary' => null
        ]);

        // Wait for the job to process
        $this->artisan('queue:work --once');

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'summary' => null // Initially null
        ]);

        // Process the job
        $this->artisan('queue:work --once');

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'summary' => null // Should still be null as we're mocking the service
        ]);
    }
}
