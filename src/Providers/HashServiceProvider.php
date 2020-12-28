<?php

declare(strict_types=1);

namespace App\Providers;

use App\Auth\Hashing\BcryptHasher;
use App\Auth\Hashing\HasherInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;

class HashServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        HasherInterface::class,
    ];

    public function register(): void
    {
        $container = $this->getContainer();

        $container->share(HasherInterface::class, static function () {
            return new BcryptHasher();
        });
    }
}
