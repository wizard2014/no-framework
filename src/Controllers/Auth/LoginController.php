<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Auth\Auth;
use App\Controllers\Controller;
use App\Session\Flash;
use App\Views\View;
use League\Route\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use function redirect;

class LoginController extends Controller
{
    private $view;
    private $auth;
    private $router;
    private $flash;

    public function __construct(View $view, Auth $auth, Router $router, Flash $flash)
    {
        $this->view = $view;
        $this->auth = $auth;
        $this->router = $router;
        $this->flash = $flash;
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
            $this->flash->now('error', 'Invalid credentials');

            return redirect($request->getUri()->getPath());
        }

        return redirect($this->router->getNamedRoute('home')->getPath());
    }
}
