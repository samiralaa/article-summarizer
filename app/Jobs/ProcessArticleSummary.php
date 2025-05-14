<?php

namespace App\Jobs;

use App\Models\Article;
use App\Notifications\ArticleSummaryReady;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\TextAnalysis\TextAnalyzerInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;

class ProcessArticleSummary implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private Article $article
    ) {}

    /**
     * Execute the job.
     */
    public function handle(TextAnalyzerInterface $textAnalyzer): void
    {
        $summary = $textAnalyzer->summarize($this->article->body);
        
        $this->article->update(['summary' => $summary]);
        
        Notification::send(
            $this->article->user,
            new ArticleSummaryReady($this->article)
        );
    }
}
