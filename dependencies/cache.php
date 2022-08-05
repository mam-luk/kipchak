<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use function Mamluk\Kipchak\config;

$container->set('cache_file', function(ContainerInterface $c) {
    $config = config('api');
    $namespace = $config['name'] ?? 'apiCache';

    return new FilesystemAdapter($namespace);
});