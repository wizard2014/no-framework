<?php

use League\Container\Container;
use League\Route\Router;

/** @var Container $container */
/** @var Router $route */

$middlewares = $container->get('config')->get('app.middleware');

foreach ($middlewares as $middleware) {
    $route->middleware($container->get($middleware));
}
