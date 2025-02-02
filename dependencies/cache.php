<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\MemcachedAdapter;

/**
 * @var $container ContainerInterface
 */

$container->set('cache.file', function(ContainerInterface $c): FilesystemAdapter {
    $config = $c->get('config')['kipchak.api'];
    $namespace = $config['name'] ?? 'KipchakApiCache';

    return new FilesystemAdapter($namespace, 3600);
});


if (isset($container->get('config')['kipchak.memcached'])) {
    $memcachedConfig = $container->get('config')['kipchak.memcached'];
    if ($memcachedConfig['enabled'] && isset($memcachedConfig['pools'])) {
        foreach ($memcachedConfig['pools'] as $poolName => $poolConnection) {
            $container->set('cache.memcached.' . $poolName, function (ContainerInterface $c) use ($memcachedConfig, $poolConnection, $poolName): MemcachedAdapter {
                $namespace = isset($c->get('config')['kipchak.api']['name']) ?? 'apiCache';
                $memcached = new Memcached($namespace);
                // Reset the list of pools in the server first before adding them
                $memcached->resetServerList();
                if (isset($memcachedConfig['pools_options'][$poolName])) {
                    foreach ($memcachedConfig['pools_options'][$poolName] as $mcOption => $mcVal) {
                        $memcached->setOption($mcOption, $mcVal);
                    }
                }
                $memcached->addServers($poolConnection);

                return new MemcachedAdapter($memcached, $namespace, 3600);
            });
        }
    }
}