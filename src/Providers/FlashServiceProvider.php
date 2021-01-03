<?php

declare(strict_types=1);

namespace App\Providers;

use App\Session\Flash;
use App\Session\SessionStoreInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;

class FlashServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        Flash::class,
    ];

    public function register(): void
    {
        $container = $this->getContainer();

        $container->share(Flash::class, static function () use ($container) {
            return new Flash(
                $container->get(SessionStoreInterface::class)
            );
        });
    }
}
