<?php

declare(strict_types=1);

namespace App\Auth\Hashing;

use RuntimeException;
use function password_hash;
use function password_needs_rehash;
use function password_verify;
use const PASSWORD_BCRYPT;

class BcryptHasher implements HasherInterface
{
    public function create(string $plain): string
    {
        $hash = password_hash($plain, PASSWORD_BCRYPT, $this->options());

        if (!$hash) {
            throw new RuntimeException('Bcrypt not supported');
        }

        return $hash;
    }

    public function check(string $plain, string $hash): bool
    {
        return password_verify($plain, $hash);
    }

    public function needRehash(string $hash): bool
    {
        return password_needs_rehash($hash, PASSWORD_BCRYPT, $this->options());
    }

    private function options(): array
    {
        return [
            'cost' => 12,
        ];
    }
}
