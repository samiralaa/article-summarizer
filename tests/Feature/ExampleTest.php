<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Article;

class ExampleTest extends TestCase
{
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
        $article = Article::factory()->create();

        $response = $this->getJson("/api/articles/{$article->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $article->id,
                     'title' => $article->title,
                     'body' => $article->body
                 ]);
    }

    public function test_summarize_article(): void
    {
        $article = Article::factory()->create([
            'title' => 'Sample Article',
            'body' => 'This is a sample article body for testing the summarization feature.'
        ]);

        $response = $this->postJson('/api/articles/summarize', [
            'article_id' => $article->id
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'summary'
                 ]);
    }
}
