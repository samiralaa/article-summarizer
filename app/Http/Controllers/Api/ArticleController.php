<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Api\StoreArticleRequest;
use App\Services\ArticleService;
class ArticleController
{
    protected $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    public function store(StoreArticleRequest $request): JsonResponse
    {
        $article = $this->articleService->createArticle($request->only(['title', 'body']));
        return response()->json($article, 201);
    }

    public function show(Article $article): JsonResponse
    {
        $articleDetails = $this->articleService->getArticleDetails($article);
        return response()->json($articleDetails);
    }
}
