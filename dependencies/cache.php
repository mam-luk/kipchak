<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

$container->set('cache_file', function(ContainerInterface $c) {
    $config = $c->get('config');
    $namespace = isset($config->get('api')['name']) ? $config->get('api')['name'] : 'apiCache';
    $cache = new FilesystemAdapter($namespace);

    return $cache;
});