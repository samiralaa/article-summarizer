<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test2@example.com',
            'password' => Hash::make('password'),
        ]);

        // Create sample articles
        $articles = [
            [
                'title' => 'The Future of AI',
                'body' => 'Artificial Intelligence is rapidly transforming our world. From machine learning to neural networks, the possibilities are endless. This article explores the current state of AI and its potential future developments.',
            ],
            [
                'title' => 'Web Development Trends 2025',
                'body' => 'The web development landscape continues to evolve. New frameworks, tools, and methodologies are emerging. This article discusses the latest trends in web development and what developers should watch out for.',
            ],
            [
                'title' => 'Sustainable Technology',
                'body' => 'As climate change becomes an increasingly pressing issue, the tech industry is focusing on sustainable solutions. This article examines how technology can help create a more sustainable future.',
            ],
        ];

        foreach ($articles as $article) {
            Article::create([
                'user_id' => $user->id,
                'title' => $article['title'],
                'body' => $article['body'],
            ]);
        }
    }
}
