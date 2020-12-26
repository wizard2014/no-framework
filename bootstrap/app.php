<?php

use League\Container\Container;

/** @var Container $container */

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

try {
    $dotenv = Dotenv\Dotenv::createUnsafeImmutable(base_path());
    $dotenv->load();
} catch (Dotenv\Exception\InvalidPathException $e) {}

require_once __DIR__ . '/container.php';

$route = $container->get(League\Route\Router::class);

require_once __DIR__ . '/../routes/web.php';

try {
    $response = $route->dispatch($container->get('request'));
} catch (Exception $e) {
    $handler = new App\Exceptions\Handler($e);

    $response = $handler->respond();
}
