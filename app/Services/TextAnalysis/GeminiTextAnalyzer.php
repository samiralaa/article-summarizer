<?php

namespace App\Services\TextAnalysis;

use Google\Cloud\Core\ExponentialBackoff;
use Google\Cloud\Core\ServiceBuilder;

class GeminiTextAnalyzer implements TextAnalyzerInterface
{
    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
    }

    public function summarize(string $text): string
    {
        $client = new \Google\Cloud\Core\ServiceBuilder([
            'key' => $this->apiKey
        ]);

        $gemini = $client->gemini();

        $prompt = "Please provide a concise summary of the following text:\n\n" . $text;

        $response = $gemini->generateContent([
            'contents' => [
                ['parts' => [['text' => $prompt]]]
            ]
        ]);

        return $response->getText();
    }
} 