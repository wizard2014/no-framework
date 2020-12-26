<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use ReflectionClass;
use function method_exists;

class Handler
{
    private $exception;

    public function __construct(Exception $exception)
    {
        $this->exception = $exception;
    }

    public function respond()
    {
        $class = (new ReflectionClass($this->exception))->getShortName();

        if (!method_exists($this, $method = "handle{$class}")) {
            $this->unhandledException($this->exception);
        }

        return $this->{$method}($this->exception);
    }

    private function handleValidationException(Exception $e)
    {
        // session set

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
