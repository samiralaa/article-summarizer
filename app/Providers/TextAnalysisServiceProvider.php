<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TextAnalysis\GeminiTextAnalyzer;
use App\Services\TextAnalysis\TextAnalyzerInterface;

class TextAnalysisServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(TextAnalyzerInterface::class, GeminiTextAnalyzer::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
