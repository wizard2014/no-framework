<?php

declare(strict_types=1);

namespace App\Auth;

use App\Auth\Hashing\HasherInterface;
use App\Auth\Provider\UserProviderInterface;
use App\Cookies\CookieJar;
use App\Models\User;
use App\Session\SessionStoreInterface;
use RuntimeException;

class Auth
{
    private $hasher;
    private $session;
    private $user;
    private $recaller;
    private $cookie;
    private $userProvider;

    public function __construct(
        HasherInterface $hasher,
        SessionStoreInterface $session,
        Recaller $recaller,
        CookieJar $cookie,
        UserProviderInterface $userProvider
    ) {
        $this->hasher = $hasher;
        $this->session = $session;
        $this->recaller = $recaller;
        $this->cookie = $cookie;
        $this->userProvider = $userProvider;
    }

    public function logout(): void
    {
        $this->userProvider->clearUserRememberToken($this->user->getId());
        $this->cookie->clear('remember');
        $this->session->clear($this->key());
    }

    public function attempt(string $username, string $password, bool $remember = false): bool
    {
        $user = $this->userProvider->getByUsername($username);

        if (!$user || !$this->hasValidCredentials($user, $password)) {
            return false;
        }

        if ($this->needsRehash($user)) {
            $this->userProvider->updatePasswordHash(
                $user->getId(),
                $this->hasher->create($password)
            );
        }

        $this->setUserSession($user);

        if ($remember) {
            $this->setRememberToken($user);
        }

        return true;
    }

    public function hasRecaller(): bool
    {
        return $this->cookie->exists('remember');
    }

    private function setRememberToken(User $user): void
    {
        [$identifier, $token] = $this->recaller->generate();

        $this->cookie->set('remember', $this->recaller->generateValueForCookies($identifier, $token));

        $this->userProvider->setUserRememberToken(
            $user->getId(),
            $identifier,
            $this->recaller->getTokenForDatabase($token)
        );
    }

    private function needsRehash(User $user): bool
    {
        return $this->hasher->needRehash($user->getPassword());
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
        $user = $this->userProvider->getById($this->session->get($this->key()));

        if (!$user) {
            throw new RuntimeException('User not found');
        }

        $this->user = $user;
    }

    public function setUserFromCookies(): void
    {
        [$identifier, $token] = $this->recaller->splitCookiesValue(
            $this->cookie->get('remember')
        );

        $user = $this->userProvider->getUserByRememberIdentifier($identifier);

        if (!$user) {
            $this->cookie->clear('remember');
            return;
        }

        if (!$this->recaller->validateToken($token, $user->getRememberToken())) {
            $this->userProvider->clearUserRememberToken($user->getId());

            $this->cookie->clear('remember');

            throw new RuntimeException('An error occurred');
        }

        $this->setUserSession($user);
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
}
