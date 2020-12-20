<?php

declare(strict_types=1);

namespace App\Controller;

use App\Views\View;
use Psr\Http\Message\ResponseInterface;

class HomeController
{
    private $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public function index(): ResponseInterface
    {
        return $this->view->render('home.html.twig', [
            'user' => [
                'id' => 1,
            ],
        ]);
    }
}
