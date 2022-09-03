<?php

use Psr\Container\ContainerInterface;
use Illuminate\Http\Client\Factory;

/**
 * @var $container ContainerInterface
 */

$container->set('http.client', function(ContainerInterface $c): Factory {
    return new Factory();
});