<?php

declare(strict_types=1);

namespace App\Security;

use App\Session\SessionStoreInterface;
use function bin2hex;
use function random_bytes;

class Csrf
{
    private $session;
    private $persistToken = true;

    public function __construct(SessionStoreInterface $session)
    {
        $this->session = $session;
    }

    public function key(): string
    {
        return '_token';
    }

    public function tokenIsValid(?string $token): bool
    {
        if (!$token) {
            return false;
        }

        return $token === $this->session->get($this->key());
    }

    public function token(): string
    {
        if (!$this->tokenNeedsToBeGenerated()) {
            return $this->getTokenFromSession();
        }

        $this->session->set(
            $this->key(),
            $token = bin2hex(random_bytes(32))
        );

        return $token;
    }

    private function getTokenFromSession(): string
    {
        return $this->session->get($this->key());
    }

    private function tokenNeedsToBeGenerated(): bool
    {
        if (!$this->session->exists($this->key())) {
            return true;
        }

        if ($this->shouldPersistToken()) {
            return false;
        }

        return $this->session->exists($this->key());
    }

    private function shouldPersistToken(): bool
    {
        return $this->persistToken;
    }
}
