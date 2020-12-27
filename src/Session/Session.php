<?php

declare(strict_types=1);

namespace App\Session;

use function is_array;

class Session implements SessionStoreInterface
{
    public function get(string $key, $default = null)
    {
        if ($this->exists($key)) {
            return $_SESSION[$key];
        }

        return $default;
    }

    public function set($key, string $value = null): void
    {
        if (is_array($key)) {
            foreach ($key as $sessionKey => $sessionValue) {
                $_SESSION[$sessionKey] = $sessionValue;
            }

            return;
        }

        $_SESSION[$key] = $value;
    }

    public function exists(string $key): bool
    {
        return isset($_SESSION[$key]) && !empty($_SESSION[$key]);
    }

    public function clear(...$key): void
    {
        foreach ($key as $sessionKey) {
            unset($_SESSION[$sessionKey]);
        }
    }
}
