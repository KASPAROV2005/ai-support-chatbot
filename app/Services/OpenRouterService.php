<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenRouterService
{
    public function chat(array $messages): string
    {
        $key = config('services.openrouter.key');
        $model = config('services.openrouter.model', 'mistralai/mistral-7b-instruct');

        if (!$key) {
            return "⚠️ OPENROUTER_API_KEY missing in .env";
        }

        $res = Http::withHeaders([
            'Authorization' => "Bearer {$key}",
            // OpenRouter recommends these:
            'HTTP-Referer'  => config('app.url', 'http://localhost'),
            'X-Title'       => config('app.name', 'Laravel'),
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => $model,
            'messages' => $messages,
            'temperature' => 0.6,
            'max_tokens' => 400,
        ]);

        if (!$res->ok()) {
            return "❌ OpenRouter error ({$res->status()}): " . substr($res->body(), 0, 300);
        }

        return data_get($res->json(), 'choices.0.message.content', '🤖 (empty reply)');
    }
}
