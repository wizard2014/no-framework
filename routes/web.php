<?php

use League\Route\Router;

/** @var Router $route */

$route->get('/', 'App\Controllers\HomeController::index')->setName('home');

$route->group('/auth', static function ($route) {
    $route->get('/signin', 'App\Controllers\Auth\LoginController::index')->setName('auth.login');
    $route->post('/signin', 'App\Controllers\Auth\LoginController::signin');
});
