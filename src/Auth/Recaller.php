<?php

declare(strict_types=1);

namespace App\Auth;

use function bin2hex;
use function explode;
use function hash;
use function random_bytes;

class Recaller
{
    private $separator = '|';

    public function generate(): array
    {
        return [
            $this->generateIdentifier(),
            $this->generateToken(),
        ];
    }

    public function validateToken(string $plain, string $hash): bool
    {
        return $this->getTokenForDatabase($plain) === $hash;
    }

    public function generateValueForCookies(string $identifier, string $token): string
    {
        return $identifier . $this->separator . $token;
    }

    public function splitCookiesValue(string $value): array
    {
        return explode($this->separator, $value);
    }

    public function getTokenForDatabase(string $token): string
    {
        return hash('sha256', $token);
    }

    private function generateIdentifier(): string
    {
        return bin2hex(random_bytes(32));
    }

    private function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }
}
