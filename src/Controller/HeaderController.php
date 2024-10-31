<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\HeaderService;
use App\Service\MenuApiClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class HeaderController extends AbstractController
{
    private $headerService;

    public function __construct(HeaderService $headerService) {
        $this->headerService = $headerService;
    }

    public function renderHeader(): void
    {
        $menuData = $this->getMenuData();

        $response = $this->render('_header.html.twig', [
            'menuData' => $menuData,
        ]);

        // dd($response);

        $response->sendContent();
    }

    private function getMenuData(): array
    {
        return $this->headerService->getMenu('fr');
    }
} 