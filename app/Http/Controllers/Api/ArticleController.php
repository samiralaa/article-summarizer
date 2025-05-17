<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\StoreArticleRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ArticleController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Store a newly created article in storage.
     */
    public function store(StoreArticleRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $article = Article::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'body' => $validated['body']
        ]);

        return response()->json($article, 201);
    }

    /**
     * Display the specified article.
     */
    public function show(Article $article): JsonResponse
    {
        return response()->json($article);
    }
}
