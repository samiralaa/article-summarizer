<?php

namespace App\Services;

use Google\Cloud\Core\ExponentialBackoff;
use Google\Cloud\Core\ServiceBuilder;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $client;
    protected $model;

    public function __construct()
    {
        $this->client = new ServiceBuilder([
            'key' => config('services.gemini.api_key'),
        ]);

        $this->model = $this->client->languageService();
    }

    public function summarize(string $text): string
    {
        try {
            $response = $this->model->analyzeEntities([
                'document' => [
                    'type' => 'PLAIN_TEXT',
                    'content' => $text,
                ],
                'encodingType' => 'UTF8',
            ]);

            // Extract key points and create a summary
            $entities = $response['entities'];
            $keyPoints = array_map(function ($entity) {
                return $entity['name'];
            }, $entities);

            // Create a summary using the key points
            $summary = "Key points: " . implode(", ", array_slice($keyPoints, 0, 5));
            
            return $summary;
        } catch (\Exception $e) {
            Log::error('Gemini API Error: ' . $e->getMessage());
            throw new \Exception('Failed to generate summary: ' . $e->getMessage());
        }
    }
}