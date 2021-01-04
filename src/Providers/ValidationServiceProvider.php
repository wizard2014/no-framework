<?php

declare(strict_types=1);

namespace App\Providers;

use App\Rules\Exists;
use Doctrine\ORM\EntityManager;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use Valitron\Validator;

class ValidationServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    public function boot(): void
    {
        $container = $this->getContainer();

        Validator::addRule('exists', static function (string $field, string $value, array $params, array $fields) use ($container): bool {
            $existsRule = new Exists($container->get(EntityManager::class));

            return $existsRule->validate($field, $value, $params, $fields);
        }, 'is already in use');
    }

    public function register(): void
    {}
}
