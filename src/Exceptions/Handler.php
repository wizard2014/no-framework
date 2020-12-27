<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Session\SessionStoreInterface;
use Exception;
use Laminas\Diactoros\Response\RedirectResponse;
use ReflectionClass;
use function method_exists;

class Handler
{
    private $exception;
    private $session;

    public function __construct(Exception $exception, SessionStoreInterface $session)
    {
        $this->exception = $exception;
        $this->session = $session;
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

    /**
     * @param  Exception $e
     * @throws Exception
     */
    private function unhandledException(Exception $e): void
    {
         throw $e;
    }
}
