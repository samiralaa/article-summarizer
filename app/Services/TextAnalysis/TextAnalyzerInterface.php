<?php

namespace App\Services\TextAnalysis;

interface TextAnalyzerInterface
{
    public function summarize(string $text): string;
} 