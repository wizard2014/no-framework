<?php

declare(strict_types=1);

namespace App\Views\Extensions;

use League\Route\Router;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PathExtension extends AbstractExtension
{
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }
    
    public function getFunctions(): array
    {
        return [
            new TwigFunction('route', [$this, 'route']),
        ];
    }

    public function route(string $name): string
    {
        return $this->router->getNamedRoute($name)->getPath();
    }
}
