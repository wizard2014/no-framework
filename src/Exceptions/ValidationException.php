<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Psr\Http\Message\ServerRequestInterface;

class ValidationException extends Exception
{
    private $request;
    private $errors;

    public function __construct(ServerRequestInterface $request, array $errors)
    {
        $this->request = $request;
        $this->errors = $errors;
    }

    public function getPath(): string
    {
        return $this->request->getUri()->getPath();
    }

    public function getOldInput()
    {
        return$this->request->getParsedBody();
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
