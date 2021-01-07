<?php

declare(strict_types=1);

namespace App\Cookies;

use function setcookie;
use function time;

class CookieJar
{
    private $path = '/';
    private $domain = '';
    private $secure = false;
    private $httpOnly = true;

    public function set(string $name, $value, int $minutes = 60): void
    {
        $expiry = time() + ($minutes * 60);

        setcookie($name, $value, $expiry, $this->path, $this->domain, $this->secure, $this->httpOnly);
    }

    public function get(string $key, $default = null)
    {
        if ($this->exists($key)) {
            return $_COOKIE[$key];
        }

        return $default;
    }

    public function exists(string $key): bool
    {
        return isset($_COOKIE[$key]) && !empty($_COOKIE[$key]);
    }

    public function clear(string $key): void
    {
        $this->set($key, '', -2628000);
    }

    public function forever(string $key, $value): void
    {
        $this->set($key, $value, 2628000);
    }
}
