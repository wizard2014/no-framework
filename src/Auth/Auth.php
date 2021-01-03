<?php

declare(strict_types=1);

namespace App\Auth;

use App\Auth\Hashing\HasherInterface;
use App\Models\User;
use App\Session\SessionStoreInterface;
use Doctrine\ORM\EntityManager;
use RuntimeException;

class Auth
{
    private $entityManager;
    private $hasher;
    private $session;
    private $user;

    public function __construct(EntityManager $entityManager, HasherInterface $hasher, SessionStoreInterface $session)
    {
        $this->entityManager = $entityManager;
        $this->hasher = $hasher;
        $this->session = $session;
    }

    public function logout(): void
    {
        $this->session->clear($this->key());
    }

    public function attempt(string $username, string $password): bool
    {
        $user = $this->getByUsername($username);

        if (!$user || !$this->hasValidCredentials($user, $password)) {
            return false;
        }

        if ($this->needsRehash($user)) {
            $this->rehashPassword($user, $password);
        }

        $this->setUserSession($user);

        return true;
    }

    private function needsRehash(User $user): bool
    {
        return $this->hasher->needRehash($user->getPassword());
    }

    private function rehashPassword(User $user, string $password): void
    {
        $user = $this->getById($user->getId());

        if (!$user) {
            return;
        }

        $hash = $this->hasher->create($password);

        $user->setPassword($hash);

        $this->entityManager->flush();
    }

    public function user(): ?User
    {
        return $this->user;
    }

    public function check(): bool
    {
        return $this->hasUserInSession();
    }

    public function hasUserInSession(): bool
    {
        return $this->session->exists($this->key());
    }

    public function setUserFromSession(): void
    {
        $user = $this->getById($this->session->get($this->key()));

        if (!$user) {
            throw new RuntimeException('User not found');
        }

        $this->user = $user;
    }

    private function setUserSession(User $user): void
    {
        $this->session->set($this->key(), $user->getId());
    }

    private function key(): string
    {
        return 'id';
    }

    private function hasValidCredentials(User $user, string $password): bool
    {
        return $this->hasher->check($password, $user->getPassword());
    }

    private function getById(int $id): ?User
    {
        /** @var User|null $user */
        $user = $this->entityManager->getRepository(User::class)->find($id);

        return $user;
    }

    private function getByUsername(string $username): ?User
    {
        /** @var User|null $user */
        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'email' => $username,
        ]);

        return $user;
    }
}
