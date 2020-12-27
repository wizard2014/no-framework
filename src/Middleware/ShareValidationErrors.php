<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Session\SessionStoreInterface;
use App\Views\View;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ShareValidationErrors implements MiddlewareInterface
{
    private $view;
    private $session;

    public function __construct(View $view, SessionStoreInterface $session)
    {
        $this->view = $view;
        $this->session = $session;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->view->share([
            'errors' => $this->session->get('errors', []),
            'old' => $this->session->get('old', []),
        ]);

        return $handler->handle($request);
    }
}