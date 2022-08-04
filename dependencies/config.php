<?php

use Psr\Container\ContainerInterface;

$container->set('config', function(ContainerInterface $c) {

    $config = new \Mamluk\Kipchak\Helpers\Config();

    return $config;
});