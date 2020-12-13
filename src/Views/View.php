<?php

namespace App\Views;

use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;

class View
{
    private $twig;

    public function __construct(TwigEnvironment $twig)
    {
        $this->twig = $twig;
    }

    public function render(string $view, array $data = []): ResponseInterface
    {
        $response = new Response();

        $response->getBody()->write(
            $this->twig->render($view, $data)
        );

        return $response;
    }
}
