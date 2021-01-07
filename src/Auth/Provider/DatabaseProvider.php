<?php

declare(strict_types=1);

namespace App\Auth\Provider;

use App\Models\User;
use Doctrine\ORM\EntityManager;

class DatabaseProvider implements UserProviderInterface
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getById(int $id): ?User
    {
        /** @var User|null $user */
        $user = $this->entityManager->getRepository(User::class)->find($id);

        return $user;
    }

    public function getByUsername(string $username): ?User
    {
        /** @var User|null $user */
        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'email' => $username,
        ]);

        return $user;
    }

    public function updatePasswordHash(int $id, string $hash): void
    {
        $user = $this->getById($id);

        if ($user) {
            $user->setPassword($hash);

            $this->entityManager->flush();
        }
    }

    public function getUserByRememberIdentifier(string $identifier): ?User
    {
        /** @var User|null $user */
        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'rememberIdentifier' => $identifier,
        ]);

        return $user;
    }

    public function clearUserRememberToken(int $id): void
    {
        $user = $this->getById($id);

        if ($user) {
            $user->setRememberIdentifier(null);
            $user->setRememberToken(null);

            $this->entityManager->flush();
        }
    }

    public function setUserRememberToken(int $id, string $identifier, string $hash): void
    {
        $user = $this->getById($id);

        if ($user) {
            $user->setRememberIdentifier($identifier);
            $user->setRememberToken($hash);

            $this->entityManager->flush();
        }
    }
}
