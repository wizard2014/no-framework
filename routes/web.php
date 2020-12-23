<?php

use League\Route\Router;

/** @var Router $route */

$route->get('/', 'App\Controllers\HomeController::index')->setName('home');

$route->group('/auth', static function () use ($route) {
    $route->get('/auth/signin', 'App\Controllers\Auth\LoginController::index')->setName('auth.login');
});
