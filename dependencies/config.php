<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use function Mamluk\Kipchak\config;

$container->set('config', function(ContainerInterface $c) {
    $configPath = realpath(__DIR__ . '/../../../../config/');

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($configPath));
    $routes = array_keys(array_filter(iterator_to_array($iterator), function($file) {
        return $file->isFile();
    }));
    $config = [];
    foreach ($routes as $route) {
        if (strpos($route, '.php') !== false) {
            $name = basename(str_replace('.php', '', $route));
            $config[$name] = config($name);
        }
    }
    return $config;
});