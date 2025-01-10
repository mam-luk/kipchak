<?php

use Mamluk\Kipchak\Components\Exceptions\Handlers\DefaultHandler;
use function Mamluk\Kipchak\config;

/**
 * @var \Psr\Container\ContainerInterface $container
 * @var \Slim\App $app
 */

$container = $app->getContainer();
$api = $container->get('config')['kipchak.api'];
$debug = (bool) $api['debug'] == true;

// Add Application middleware
$errorMiddleware = $app->addErrorMiddleware($debug, $debug, $debug);

// Configure error middleware
$errorMiddleware->setDefaultErrorHandler(
    new DefaultHandler(
        $app->getCallableResolver(),
        $app->getResponseFactory()
    )
);
