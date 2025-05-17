<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticleController;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show']);

Route::post('/sanctum/token', [\App\Http\Controllers\Api\AuthController::class, 'generateToken']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/articles', [ArticleController::class, 'store'])
        ->middleware('throttle:10,1'); // 10 requests per minute
    Route::get('/articles/{article}', [ArticleController::class, 'show']);
});