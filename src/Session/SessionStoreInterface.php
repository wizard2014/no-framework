<?php

namespace App\Session;

interface SessionStoreInterface
{
    public function get(string $key, $default = null);

    public function set($key, string $value = null): void;

    public function exists(string $key): bool;

    public function clear(...$key): void;
}
