<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Session\SessionStoreInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ClearValidationErrors implements MiddlewareInterface
{
    private $session;

    public function __construct(SessionStoreInterface $session)
    {
        $this->session = $session;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->session->clear('errors', 'old');

        return $handler->handle($request);
    }
}
