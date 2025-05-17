<?php

namespace App\Services\TextAnalysis;

interface TextAnalyzerInterface
{
    /**
     * Generate a summary for the given text
     *
     * @param string $text
     * @return string
     */
    public function generate(string $text): string;
} 