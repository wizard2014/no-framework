<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Exceptions\CsrfTokenException;
use App\Security\Csrf;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use function in_array;

class CsrfGuard implements MiddlewareInterface
{
    private $csrf;

    public function __construct(Csrf $csrf)
    {
        $this->csrf = $csrf;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$this->requestRequiresProtection($request)) {
            return $handler->handle($request);
        }

        if (!$this->csrf->tokenIsValid($this->getTokenFromRequest($request))) {
            throw new CsrfTokenException('An error occurred');
        }
        
        return $handler->handle($request);
    }

    private function getTokenFromRequest(ServerRequestInterface $request): ?string
    {
        return $request->getParsedBody()[$this->csrf->key()] ?? null;
    }

    private function requestRequiresProtection(ServerRequestInterface $request): bool
    {
        return in_array($request->getMethod(), ['POST', 'PUT', 'DELETE', 'PATCH'], true);
    }
}
