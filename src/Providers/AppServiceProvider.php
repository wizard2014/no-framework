<?php

declare(strict_types=1);

namespace App\Providers;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;

class AppServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        Router::class,
        'response',
        'request',
        'emitter',
    ];

    public function register(): void
    {
        $container = $this->getContainer();

        $container->share(Router::class, static function () use ($container) {
            $strategy = (new ApplicationStrategy())->setContainer($container);

            return (new Router())->setStrategy($strategy);
        });

        $container->share('response', Response::class);

        $container->share('request', static function () {
            return ServerRequestFactory::fromGlobals(
                $_SERVER,
                $_GET,
                $_POST,
                $_COOKIE,
                $_FILES
            );
        });

        $container->share('emitter', SapiEmitter::class);
    }
}
