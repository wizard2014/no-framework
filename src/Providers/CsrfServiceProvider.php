<?php

declare(strict_types=1);

namespace App\Providers;

use App\Security\Csrf;
use App\Session\SessionStoreInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;

class CsrfServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        Csrf::class,
    ];

    public function register(): void
    {
        $container = $this->getContainer();

        $container->share(Csrf::class, static function () use ($container) {
            return new Csrf(
                $container->get(SessionStoreInterface::class)
            );
        });
    }
}
