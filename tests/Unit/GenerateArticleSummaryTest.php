<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Jobs\GenerateArticleSummary;
use App\Models\Article;
use App\Models\User;
use App\Services\TextAnalysis\TextAnalyzerInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GenerateArticleSummaryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
    }

    public function test_job_generates_and_saves_summary(): void
    {
        // Create a user and article
        $user = User::factory()->create();
        $article = Article::factory()->create([
            'user_id' => $user->id,
            'title' => 'Test Article',
            'body' => 'This is a test article body.',
            'summary' => null
        ]);

        // Mock the text analyzer
        $mockAnalyzer = $this->mock(TextAnalyzerInterface::class);
        $mockAnalyzer->shouldReceive('generate')
            ->once()
            ->with($article->body)
            ->andReturn('Test summary');

        // Run the job
        $job = new GenerateArticleSummary($article);
        $job->handle($mockAnalyzer);

        // Assert the summary was saved
        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'summary' => 'Test summary'
        ]);
    }
} 