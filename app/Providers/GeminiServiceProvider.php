<?php

namespace App\Providers;

use App\Services\GeminiService;
use Illuminate\Support\ServiceProvider;
use App\Services\TextAnalysis\TextAnalyzerInterface;

class GeminiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TextAnalyzerInterface::class, function ($app) {
            return new GeminiService();
        });
    }
} 