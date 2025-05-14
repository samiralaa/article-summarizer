# Article Summarizer API

A Laravel 12 API that uses Google's Gemini AI to automatically summarize articles.

## Requirements

- PHP 8.1 or higher
- Composer
- MySQL or PostgreSQL
- Google Cloud account with Gemini API access

## Installation

1. Clone the repository
2. Copy `.env.example` to `.env` and configure your environment variables:
   ```
   cp .env.example .env
   ```
3. Add your Gemini API key to `.env`:
   ```
   GEMINI_API_KEY=your_gemini_api_key_here
   ```
4. Install dependencies:
   ```
   composer install
   ```
5. Generate application key:
   ```
   php artisan key:generate
   ```
6. Run migrations:
   ```
   php artisan migrate
   ```

## Queue Configuration

This application uses the database queue driver. Configure your `.env`:
```
QUEUE_CONNECTION=database
```

Create the jobs table:
```
php artisan queue:table
php artisan migrate
```

Start the queue worker:
```
php artisan queue:work
```

## API Endpoints

### Authentication
- POST /api/sanctum/token
  - Body: email, password, device_name

### Articles
- POST /api/articles
  - Protected by Sanctum
  - Rate limited to 10 requests per minute
  - Body: title, body
- GET /api/articles/{id}
  - Protected by Sanctum
  - Returns article with summary

## Testing

Run the test suite:
```
php artisan test
```
