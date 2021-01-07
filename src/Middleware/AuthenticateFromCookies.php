<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Auth\Auth;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthenticateFromCookies implements MiddlewareInterface
{
    private $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->auth->check()) {
            return $handler->handle($request);
        }

        if ($this->auth->hasRecaller()) {
            try {
                $this->auth->setUserFromCookies();
            } catch (Exception $e) {
                $this->auth->logout();
            }
        }

        return $handler->handle($request);
    }
}
