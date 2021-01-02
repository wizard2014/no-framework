<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Auth\Auth;
use App\Controllers\Controller;
use App\Views\View;
use League\Route\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class LoginController extends Controller
{
    private $view;
    private $auth;
    private $router;

    public function __construct(View $view, Auth $auth, Router $router)
    {
        $this->view = $view;
        $this->auth = $auth;
        $this->router = $router;
    }

    public function index(): ResponseInterface
    {
        return $this->view->render('auth/login.html.twig');
    }

    public function signin(ServerRequestInterface $request): ResponseInterface
    {
        $data = $this->validate($request, [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $attempt = $this->auth->attempt($data['email'], $data['password']);

        if (!$attempt) {
            // failed
        }

        return redirect($this->router->getNamedRoute('home')->getPath());
    }
}
