<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Auth\Auth;
use App\Controllers\Controller;
use Psr\Http\Message\ResponseInterface;
use function redirect;

class LogoutController extends Controller
{
    private $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function logout(): ResponseInterface
    {
        $this->auth->logout();

        return redirect('/');
    }
}
