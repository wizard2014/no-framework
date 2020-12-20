<?php

declare(strict_types=1);

namespace App\Providers;

use App\Views\View;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Twig\Loader\FilesystemLoader as TwigLoader;
use Twig\Environment as TwigEnvironment;

class ViewServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        View::class,
    ];

    public function register(): void
    {
        $container = $this->getContainer();

        $container->share(View::class, static function () {
            $loader = new TwigLoader(base_path('templates'));

            $twig = new TwigEnvironment($loader, [
                'cache' => false,
            ]);

            return new View($twig);
        });
    }
}