<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class ArticleService
{
    public function createArticle(array $data): Article
    {
        return Article::create([
            'title' => $data['title'],
            'body' => $data['body'],
            'user_id' => Auth::id(),
        ]);
    }

    public function getArticleDetails(Article $article): array
    {
        return [
            'title' => $article->title,
            'body' => $article->body,
            'user' => $article->user->name,
            'summary' => $article->summary,
        ];
    }
}