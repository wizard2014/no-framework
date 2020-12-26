<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Views\View;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class LoginController extends Controller
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

    public function signin(ServerRequestInterface $request): ResponseInterface
    {
        $this->validate($request, [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
    }
}
