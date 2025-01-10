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
$logDetails = isset($api['logExceptionDetails']) && (bool) $api['logExceptionDetails'];

// Add Application middleware
$errorMiddleware = $app->addErrorMiddleware($debug, $debug, $logDetails);

// Configure error middleware
$errorMiddleware->setDefaultErrorHandler(
    new DefaultHandler(
        $app->getCallableResolver(),
        $app->getResponseFactory()
    )
);
