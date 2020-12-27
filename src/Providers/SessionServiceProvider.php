<?php

declare(strict_types=1);

namespace App\Providers;

use App\Session\Session;
use App\Session\SessionStoreInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;

class SessionServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        SessionStoreInterface::class,
    ];

    public function register()
    {
        $container = $this->getContainer();

        $container->share(SessionStoreInterface::class, static function () {
            return new Session();
        });
    }
}
