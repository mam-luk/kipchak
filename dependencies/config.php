<?php

use Psr\Container\ContainerInterface;
use Mamluk\Kipchak\Components\Helpers\Config;

$container->set('config', function(ContainerInterface $c) {

    $config = new Config();

    return $config;
});