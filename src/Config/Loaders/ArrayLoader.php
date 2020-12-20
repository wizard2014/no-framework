<?php

declare(strict_types=1);

namespace App\Config\Loaders;

use Exception;

class ArrayLoader implements LoaderInterface
{
    private $files;

    public function __construct(array $files)
    {
        $this->files = $files;
    }

    public function parse(): array
    {
        $parsed = [];

        foreach ($this->files as $namespace => $path) {
            try {
                $parsed[$namespace] = require $path;
            } catch (Exception $e) {}
        }

        return $parsed;
    }
}
