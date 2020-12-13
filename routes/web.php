<?php

use League\Route\Router;

/** @var Router $route */

$route->get('/', 'App\Controller\HomeController::index')->setName('home');
