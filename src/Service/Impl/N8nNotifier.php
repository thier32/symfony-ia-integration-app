<?php

namespace App\Service\Impl;

use App\Service\IN8nNotifier;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class N8nNotifier implements IN8nNotifier
{
    private HttpClientInterface $httpClient;
    private string $n8nWebhookUrl;
    private string $symfonyToken;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->n8nWebhookUrl = $_ENV['N8N_WEBHOOK_URL'];
    }

    public function notify(): void
    {
        try {
            $this->httpClient->request('POST', $this->n8nWebhookUrl, []);
        } catch (\Exception $e) {
        }
    }
}
