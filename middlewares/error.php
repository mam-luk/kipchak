<?php

use Mamluk\Kipchak\Components\Exceptions\Handlers\DefaultHandler;
use function Mamluk\Kipchak\config;

$container = $app->getContainer();
$api = $container->get('config')['kipchak.api'];
$debug = (bool) $api['debug'] == true;

// Add Application middleware
$errorMiddleware = $app->addErrorMiddleware($debug, true, true);

// Configure error middleware
$errorMiddleware->setDefaultErrorHandler(
    new DefaultHandler(
        $app->getCallableResolver(),
        $app->getResponseFactory()
    )
);
