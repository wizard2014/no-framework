<?php

use League\Container\Container;
use League\Route\Router;

/** @var Container $container */
/** @var Router $route */

$route->get('/', 'App\Controllers\HomeController::index')->setName('home');

$route->group('', static function ($route) {
    $route->get('/dashboard', 'App\Controllers\DashboardController::index')->setName('dashboard');

    $route->post('/auth/logout', 'App\Controllers\Auth\LogoutController::logout')->setName('auth.logout');
})->middleware($container->get(App\Middleware\Authenticated::class));

$route->group('/auth', static function ($route) {
    $route->get('/signin', 'App\Controllers\Auth\LoginController::index')->setName('auth.login');
    $route->post('/signin', 'App\Controllers\Auth\LoginController::signin');

    $route->get('/register', 'App\Controllers\Auth\RegisterController::index')->setName('auth.register');
    $route->post('/register', 'App\Controllers\Auth\RegisterController::register');
})->middleware($container->get(App\Middleware\Guest::class));
