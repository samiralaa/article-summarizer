<?php

namespace App\Jobs;

use App\Models\Article;
use App\Notifications\ArticleSummaryGenerated;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\TextAnalysis\TextAnalyzerInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class GenerateArticleSummary implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Article $article
    ) {}

    /**
     * Execute the job.
     */
    public function handle(TextAnalyzerInterface $analyzer): void
    {
        try {
            Log::info('Starting article summary generation', [
                'article_id' => $this->article->id,
                'title' => $this->article->title
            ]);

            $summary = $analyzer->generate($this->article->body);
            
            Log::info('Summary generated successfully', [
                'article_id' => $this->article->id,
                'summary_length' => strlen($summary)
            ]);

            $this->article->update(['summary' => $summary]);
            
            $this->article->user->notify(new ArticleSummaryGenerated($this->article));

            Log::info('Article updated and notification sent', [
                'article_id' => $this->article->id
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating article summary', [
                'article_id' => $this->article->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }
} 