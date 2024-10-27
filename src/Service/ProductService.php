<?php
// src/Service/ProductService.php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ProductService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getProducts(): array
    {
        $response = $this->client->request('GET', 'http://localhost/api/v2/shop/products');

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Failed to fetch products');
        }

        $data = $response->toArray();

        return $data['hydra:member'] ?? [];
    }

    public function getProduct(string $code): ?array
    {
        $response = $this->client->request('GET', 'http://localhost/api/v2/shop/products/' . $code);

        if ($response->getStatusCode() !== 200) {
            return null;
        }

        $product = $response->toArray();
        $product['options'] = $this->getProductOptions($product['options']);
        $product['variants'] = $this->getVariants($product['variants']);

        return $product;
    }

    public function getProductOptions(array $optionUrls): array
    {
        $options = [];
        foreach ($optionUrls as $optionUrl) {
            if (is_string($optionUrl) && strpos($optionUrl, 'http') !== 0) {
                $optionUrl = 'http://localhost' . $optionUrl;
            }
            $response = $this->client->request('GET', $optionUrl);
            if ($response->getStatusCode() === 200) {
                $option = $response->toArray();
                $option['values'] = $this->getOptionValues($option['values']);
                $options[] = $option;
            }
        }
        return $options;
    }

    public function getOptionValues(array $valueUrls): array
    {
        $values = [];
        foreach ($valueUrls as $valueUrl) {
            if (is_string($valueUrl) && strpos($valueUrl, 'http') !== 0) {
                $valueUrl = 'http://localhost' . $valueUrl;
            }
            $response = $this->client->request('GET', $valueUrl);
            if ($response->getStatusCode() === 200) {
                $values[] = $response->toArray();
            }
        }
        return $values;
    }

    public function getVariants(array $variantUrls): array
    {
        // Filtrer uniquement les URLs valides commençant par "/api/"
        $variantUrls = array_filter($variantUrls, function ($variantUrl) {
            return is_string($variantUrl) && strpos($variantUrl, '/api/') === 0;
        });

        $variants = [];
        foreach ($variantUrls as $variantUrl) {
            // Construire l'URL complète si nécessaire
            $variantUrl = strpos($variantUrl, 'http') !== 0 ? 'http://localhost' . $variantUrl : $variantUrl;

            // Effectuer la requête HTTP pour obtenir les détails du variant
            $response = $this->client->request('GET', $variantUrl);
            if ($response->getStatusCode() === 200) {
                $variant = $response->toArray();
                if (isset($variant['optionValues'])) {
                    $variant['optionValues'] = $this->getOptionValueCodes($variant['optionValues']);
                } else {
                    $variant['optionValues'] = [];
                }
                $variants[] = $variant;
            } else {
                // Log ou gérer les erreurs de requête HTTP
                $this->logger->error('Failed to fetch variant', ['variantUrl' => $variantUrl]);
            }
        }
        return $variants;
    }

    public function getOptionValueCodes(array $valueUrls): array
    {
        $codes = [];
        foreach ($valueUrls as $valueUrl) {
            if (is_string($valueUrl) && strpos($valueUrl, 'http') !== 0) {
                $valueUrl = 'http://localhost' . $valueUrl;
            }
            $response = $this->client->request('GET', $valueUrl);
            if ($response->getStatusCode() === 200) {
                $value = $response->toArray();
                $codes[] = $value['code'];
            }
        }
        return $codes;
    }

    public function getVariant(string $variantUrl): ?array
    {
        if (strpos($variantUrl, 'http') !== 0) {
            $variantUrl = 'http://localhost' . $variantUrl;
        }
        $response = $this->client->request('GET', $variantUrl);
        if ($response->getStatusCode() !== 200) {
            return null;
        }
        return $response->toArray();
    }


    public function getProductDescription(string $code, string $locale = 'fr_FR'): ?array
    {
        $response = $this->client->request('GET', 'http://localhost/api/v2/shop/product/' . $code . '/description/' . $locale);
        if ($response->getStatusCode() !== 200) {
            return null;
        }

        return $response->toArray();
    }

    public function getProductFeatures(string $code, string $locale = 'fr_FR'): ?array
    {
        $response = $this->client->request('GET', 'http://localhost/api/v2/shop/product/' . $code . '/features/' . $locale);
        if ($response->getStatusCode() !== 200) {
            return null;
        }

        return $response->toArray();
    }
}
