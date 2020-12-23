<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Views\View;
use Psr\Http\Message\ResponseInterface;

class LoginController
{
    private $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public function index(): ResponseInterface
    {
        return $this->view->render('auth/login.html.twig');
    }
}
