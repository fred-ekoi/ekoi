<?php

declare(strict_types=1);

namespace App\Twig;

use App\Controller\HeaderController;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MenuExtension extends AbstractExtension
{
    public function __construct(
        private HeaderController $headerController,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('render_menu', [$this->headerController, 'renderHeader'], ['is_safe' => ['html']]),
        ];
    }
} 