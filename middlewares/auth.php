<?php

use Mamluk\Kipchak\Components\Middlewares\AuthJwks;
use Mamluk\Kipchak\Components\Middlewares\AuthKey;

$container = $app->getContainer();
$api = $container->get('config')['api'];
if ($api['auth']['jwks']['enabled'] && isset($request->getHeader('Authorization')[0])) {
    // Enable JWKS globally
    $app->add(new AuthJwks($container));
}

if ($api['auth']['key']['enabled']) {
    // Enable Key based auth globally
    $app->add(new AuthKey($container));

}
