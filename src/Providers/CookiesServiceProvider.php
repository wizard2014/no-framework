<?php

declare(strict_types=1);

namespace App\Providers;

use App\Cookies\CookieJar;
use League\Container\ServiceProvider\AbstractServiceProvider;

class CookiesServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        CookieJar::class,
    ];

    public function register(): void
    {
        $container = $this->getContainer();

        $container->share(CookieJar::class, static function () {
            return new CookieJar();
        });
    }
}
