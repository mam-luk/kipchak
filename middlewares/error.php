<?php

use Meezaan\Microservice\Components\Exceptions\Handlers\DefaultHandler;

$config = $container->get('config');
$api = $config->get('api');
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
