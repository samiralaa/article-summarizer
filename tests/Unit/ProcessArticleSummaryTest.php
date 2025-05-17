<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Jobs\ProcessArticleSummary;
use App\Models\Article;
use App\Models\User;
use App\Services\TextAnalysis\TextAnalyzerInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProcessArticleSummaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_processes_article_summary(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create([
            'user_id' => $user->id,
            'title' => 'Test Article',
            'body' => 'This is a test article body that needs to be summarized.',
            'summary' => null,
        ]);

        $textAnalyzer = $this->mock(TextAnalyzerInterface::class);
        $textAnalyzer->shouldReceive('summarize')
            ->once()
            ->with($article->body)
            ->andReturn('Test summary');

        $job = new ProcessArticleSummary($article);
        $job->handle($textAnalyzer);

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'summary' => 'Test summary',
        ]);
    }
}