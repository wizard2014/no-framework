<?php

declare(strict_types=1);

namespace App\Providers;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;
use League\Container\ServiceProvider\AbstractServiceProvider;
use function env;

class DatabaseServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        EntityManager::class,
    ];

    public function register(): void
    {
        $container = $this->getContainer();

        $config = $container->get('config');

        $container->share(EntityManager::class, static function () use ($config) {
            $dbConfig = Setup::createConfiguration($config->get('app.debug'));
            $driver = new AnnotationDriver(new AnnotationReader());

            $dbConfig->setMetadataDriverImpl($driver);

            $connectionParams = $config->get('db.' . env('DB_TYPE'));

            return EntityManager::create($connectionParams, $dbConfig);
        });
    }
}