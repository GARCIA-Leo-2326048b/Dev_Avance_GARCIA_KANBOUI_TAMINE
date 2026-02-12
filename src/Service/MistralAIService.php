<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Le service MistralAIService permet d’interagir avec l’API Mistral AI.
 * Il envoie un prompt au modèle de langage via une requête HTTP
 * et retourne la réponse générée sous forme de tableau.
 */
class MistralAIService
{
    private $httpClient;

    /**
     * Constructeur du service.
     * Il injecte le client HTTP Symfony utilisé pour effectuer
     * les requêtes vers l’API Mistral.
     *
     * @param HttpClientInterface $httpClient Client HTTP pour les appels API
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Envoie un prompt à l’API Mistral et retourne la réponse générée.
     * La méthode construit une requête POST vers l’endpoint
     * https://api.mistral.ai/v1/chat/completions,
     * en utilisant la clé API définie dans les variables d’environnement.
     * Elle transmet le prompt sous forme de message utilisateur
     * et retourne la réponse décodée au format tableau.
     *
     * @param string $prompt Texte envoyé au modèle d’intelligence artificielle
     * @return array Réponse de l’API sous forme de tableau associatif
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
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
