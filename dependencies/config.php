<?php

use Psr\Container\ContainerInterface;

$container->set('config', function(ContainerInterface $c) {

    $config = new \Meezaan\Microservice\Helpers\Config();

    return $config;
});