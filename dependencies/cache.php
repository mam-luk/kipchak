<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\MemcachedAdapter;

$container->set('cache_file', function(ContainerInterface $c) {
    $config = $c->get('config')['kipchak_api'];
    $namespace = $config['name'] ?? 'KipchakApiCache';

    return new FilesystemAdapter($namespace, 3600);
});

if (isset($container->get('config')['kipchak_memcached'])) {
    $memcachedConfig = $container->get('config')['kipchak_memcached'];

    if ($memcachedConfig['enabled'] && isset($memcachedConfig['pools'])) {
        foreach ($memcachedConfig['pools'] as $poolName => $poolConnection) {
            $container->set('cache_memcached_' . $poolName, function (ContainerInterface $c) use ($memcachedConfig, $poolConnection) {
                $namespace = isset($c->get('config')['kipchak_api']['name']) ?? 'apiCache';

                $memcached = new Memcached($namespace);
                $memcached->setOption(Memcached::OPT_CONNECT_TIMEOUT, 10);
                $memcached->setOption(Memcached::OPT_DISTRIBUTION, Memcached::DISTRIBUTION_CONSISTENT);
                $memcached->setOption(Memcached::OPT_SERVER_FAILURE_LIMIT, 2);
                $memcached->setOption(Memcached::OPT_REMOVE_FAILED_SERVERS, true);
                $memcached->setOption(Memcached::OPT_RETRY_TIMEOUT, 1);
                $memcached->addServers($poolConnection);

                return new MemcachedAdapter($memcached, $namespace, 3600);
            });
        }
    }
}