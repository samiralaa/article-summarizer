<?php
/**+++++++++++++++++++++
 * Gemini Service
 * Remah Digital LLC
 * ++++++++++++++++++++
 */
namespace App\Services;

use App\Services\TextAnalysis\TextAnalyzerInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService implements TextAnalyzerInterface
{
    protected $model;
    protected $apiKey;
    protected $endPoint;

    public function __construct()
    {
        $this->model = "gemini-2.0-flash";
        $this->apiKey = config('services.gemini.api_key');
        
        if (empty($this->apiKey)) {
            throw new \RuntimeException('Gemini API key is not configured');
        }
        
        $this->endPoint = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}";
    }

    public function generate(string $articleText): string
    {
        try {
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
                "Article content: {$articleText}"
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

            if (!$response->successful()) {
                Log::error('Gemini API error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \RuntimeException('Failed to generate summary: ' . $response->body());
            }

            $result = $response->json();
            
            if (!isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                Log::error('Invalid Gemini API response', ['response' => $result]);
                throw new \RuntimeException('Invalid response format from Gemini API');
            }

            return $result['candidates'][0]['content']['parts'][0]['text'];
        } catch (\Exception $e) {
            Log::error('Error in GeminiService', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}