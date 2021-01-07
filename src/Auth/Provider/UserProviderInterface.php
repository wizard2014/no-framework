<?php

declare(strict_types=1);

namespace App\Auth\Provider;

use App\Models\User;

interface UserProviderInterface
{
    public function getById(int $id): ?User;

    public function getByUsername(string $username): ?User;

    public function updatePasswordHash(int $id, string $hash): void;

    public function getUserByRememberIdentifier(string $identifier): ?User;

    public function clearUserRememberToken(int $id): void;

    public function setUserRememberToken(int $id, string $identifier, string $hash): void;
}
