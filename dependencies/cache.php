<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use function Mamluk\Kipchak\Components\Helpers\config;

$container->set('cache_file', function(ContainerInterface $c) {
    $config = config('api');
    $namespace = isset($config->get('api')['name']) ? $config->get('api')['name'] : 'apiCache';
    $cache = new FilesystemAdapter($namespace);

    return $cache;
});