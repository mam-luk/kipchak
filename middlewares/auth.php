<?php

use Mamluk\Kipchak\Components\Middlewares\AuthJwks;
use Mamluk\Kipchak\Components\Middlewares\AuthKey;

$container = $app->getContainer();
if (isset($container->get('config')['kipchak_auth'])) {
    $auth = $container->get('config')['kipchak_auth'];
    if ($auth['jwks']['enabled']) {
        // Enable JWKS globally
        $app->add(new AuthJwks($container));
    }

    if ($auth['key']['enabled']) {
        // Enable Key based auth globally
        $app->add(new AuthKey($container));

    }
}
