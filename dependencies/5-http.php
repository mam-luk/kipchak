<?php

use Psr\Container\ContainerInterface;
use Illuminate\Http\Client\Factory;

$container->set('http', function(ContainerInterface $c) {
    return new Factory();
});