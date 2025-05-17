<?php

namespace App\Services\TextAnalysis;

use Illuminate\Support\Facades\Http;

class GeminiTextAnalyzer implements TextAnalyzerInterface
{
    private string $model;
    private string $apiKey;
    private string $endPoint;

    public function __construct()
    {
        $this->model = "gemini-2.0-flash";
        $this->apiKey = config('services.gemini.api_key');
        $this->endPoint = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}";
    }

    public function generate(string $text): string
    {
        $promptParts = [
            "Generate a concise summary of the following article focusing on main arguments, key findings, and conclusions. ",
            "Tailor for Arab readers in Saudi Arabia. ",
            "##Requirements:",
            "- Maintain accuracy and core message ",
            "- Highlight significant data/statistics ",
            "- Use clear language in ".app()->getLocale(),
            "- Avoid technical jargon ",
            "- No external commentary ",
            "- Pure text only ",
            "- 150-200 words ",
            "Article content: {$text}"
        ];

        $prompt = implode('', $promptParts);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->endPoint, [
            'contents' => [
                'parts' => [
                    ['text' => $prompt]
                ]
            ]
        ]);

        $result = $response->json();
        return $result['candidates'][0]['content']['parts'][0]['text'];
    }
}