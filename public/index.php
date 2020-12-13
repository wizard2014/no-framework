<?php

require_once __DIR__ . '/../bootstrap/app.php';

/** @var Laminas\HttpHandlerRunner\Emitter\SapiEmitter $emitter */
$emitter = $container->get('emitter');

$emitter->emit($response);
