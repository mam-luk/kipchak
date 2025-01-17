<?php

use Mamluk\Kipchak\Components\Exceptions\Handlers\DefaultHandler;

/**
 * @var \Psr\Container\ContainerInterface $container
 * @var \Slim\App $app
 */

$container = $app->getContainer();
$api = $container->get('config')['kipchak.api'];
$logExceptions = isset($api['logExceptions']) && (bool) $api['logExceptions'];
$logExceptionDetails = isset($api['logExceptionDetails']) && (bool) $api['logExceptionDetails'];

// Add Application middleware
$errorMiddleware = $app->addErrorMiddleware($logExceptions, $logExceptions, $logExceptionDetails);

// Configure error middleware
$errorMiddleware->setDefaultErrorHandler(
    new DefaultHandler(
        $app->getCallableResolver(),
        $app->getResponseFactory()
    )
);
