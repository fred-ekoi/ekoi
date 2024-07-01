<?php // src/Controller/ApiController.php
namespace App\Controller;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    public function getProducts()
    {
        $client = new Client();
        $response = $client->request('GET', 'http://localhost:8000/api/products');
        $products = json_decode($response->getBody(), true);

        return new JsonResponse($products);
    }
}
