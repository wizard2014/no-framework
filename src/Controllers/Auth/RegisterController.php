<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Auth\Auth;
use App\Auth\Hashing\HasherInterface;
use App\Controllers\Controller;
use App\Factory\UserFactory;
use App\Models\User;
use App\Views\View;
use Doctrine\ORM\EntityManager;
use League\Route\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use function redirect;

class RegisterController extends Controller
{
    private $view;
    private $hasher;
    private $entityManager;
    private $router;
    private $auth;

    public function __construct(
        View $view,
        HasherInterface $hasher,
        EntityManager $entityManager,
        Router $router,
        Auth $auth
    ) {
        $this->view = $view;
        $this->hasher = $hasher;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->auth = $auth;
    }

    public function index(): ResponseInterface
    {
        return $this->view->render('auth/register.html.twig');
    }

    public function register(ServerRequestInterface $request): ResponseInterface
    {
        $data = $this->validateRegistration($request);

        $this->createUser($data);

        if (!$attempt = $this->auth->attempt($data['email'], $data['password'])) {
            return redirect('/');
        }

        return redirect($this->router->getNamedRoute('home')->getPath());
    }

    private function createUser(array $data): User
    {
        $data['password'] = $this->hasher->create($data['password']);

        $user = UserFactory::fromRequest($data);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    private function validateRegistration(ServerRequestInterface $request)
    {
        return $this->validate($request, [
            'email' => ['required', 'email', ['exists', User::class]],
            'name' => ['required'],
            'password' => ['required'],
            'password_confirmation' => ['required', ['equals', 'password']],
        ]);
    }
}
