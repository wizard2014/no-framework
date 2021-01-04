<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Session\SessionStoreInterface;
use App\Views\View;
use Exception;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use ReflectionClass;
use function method_exists;
use function redirect;

class Handler
{
    private $exception;
    private $session;
    private $view;

    public function __construct(Exception $exception, SessionStoreInterface $session, View $view)
    {
        $this->exception = $exception;
        $this->session = $session;
        $this->view = $view;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function respond()
    {
        $class = (new ReflectionClass($this->exception))->getShortName();

        if (!method_exists($this, $method = "handle{$class}")) {
            $this->unhandledException($this->exception);
        }

        return $this->{$method}($this->exception);
    }

    private function handleValidationException(Exception $e): RedirectResponse
    {
        $this->session->set([
            'errors' => $e->getErrors(),
            'old' => $e->getOldInput(),
        ]);

        return redirect($e->getPath());
    }

    private function handleCsrfTokenException(Exception $e): ResponseInterface
    {
        return $this->view->render('errors/csrf.html.twig');
    }

    /**
     * @param  Exception $e
     * @throws Exception
     */
    private function unhandledException(Exception $e): void
    {
         throw $e;
    }
}
