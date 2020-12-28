<?php

declare(strict_types=1);

namespace App\Providers;

use App\Views\Extensions\PathExtension;
use App\Views\View;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\Router;
use Twig\Environment as TwigEnvironment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader as TwigLoader;

class ViewServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        View::class,
    ];

    public function register(): void
    {
        $container = $this->getContainer();

        $config = $container->get('config');

        $container->share(View::class, static function () use ($container, $config) {
            $loader = new TwigLoader(base_path('templates'));

            $twig = new TwigEnvironment($loader, [
                'cache' => $config->get('cache.views.path'),
                'debug' => $config->get('app.debug'),
            ]);

            if ($config->get('app.debug')) {
                $twig->addExtension(new DebugExtension());
            }

            $twig->addExtension(new PathExtension($container->get(Router::class)));

            return new View($twig);
        });
    }
}
