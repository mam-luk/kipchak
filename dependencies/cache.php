<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

$container->set('cache_file', function(ContainerInterface $c) {
    $config = $c->get('config')['api'];
    $namespace = $config['name'] ?? 'apiCache';

    return new FilesystemAdapter($namespace);
});