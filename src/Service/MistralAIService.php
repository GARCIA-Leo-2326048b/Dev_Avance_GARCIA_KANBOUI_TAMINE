<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class MistralAIService
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function askMistral(string $prompt): array
    {
        $response = $this->httpClient->request('POST', 'https://api.mistral.ai/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $_ENV['MISTRAL_API_KEY'],
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode([
                'model' => 'mistral-tiny',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
            ]),
        ]);

        return $response->toArray();
    }
}
