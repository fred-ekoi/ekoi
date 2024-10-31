<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class HeaderService
{
    private $client;
    private $apiUrl;

    public function __construct(HttpClientInterface $client, string $apiUrl) {
        $this->client = $client;
        $this->apiUrl = $apiUrl;
    }

    public function getMenu(string $locale): array
    {
        $response = $this->client->request('GET', $this->apiUrl . '/api/v2/shop/menus/' . $locale);
        
        return $response->toArray();
    }
}