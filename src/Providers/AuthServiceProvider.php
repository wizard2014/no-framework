<?php

declare(strict_types=1);

namespace App\Providers;

use App\Auth\Auth;
use App\Auth\Hashing\HasherInterface;
use App\Session\SessionStoreInterface;
use Doctrine\ORM\EntityManager;
use League\Container\ServiceProvider\AbstractServiceProvider;

class AuthServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        Auth::class,
    ];

    public function register(): void
    {
        $container = $this->getContainer();

        $container->share(Auth::class, static function () use ($container) {
            return new Auth(
                $container->get(EntityManager::class),
                $container->get(HasherInterface::class),
                $container->get(SessionStoreInterface::class)
            );
        });
    }
}
