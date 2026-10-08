<?php

namespace App\Services;

use OpenAI;

class OpenAIService
{
    private $client;

    public function __construct()
    {
        $this->client = OpenAI::client($_ENV['OPENAI_API_KEY']);
    }

    public function gerarTexto($prompt)
    {
        $response = $this->client->completions()->create([
            'model' => 'gpt-3.5-turbo',  // Ou outro modelo como 'gpt-3.5-turbo'
            'prompt' => $prompt,
            'max_tokens' => 100,
        ]);

        return $response['choices'][0]['text'] ?? 'Erro na resposta';
    }
}
