<?php

use Psr\Container\ContainerInterface;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Doctrine\ORM\ORMSetup;
use Doctrine\DBAL\Connection;
use Monolog\Logger;

/**
 * @var $container ContainerInterface
 */

// Create all Doctrine DBAL Connections
if (isset($container->get('config')['kipchak.doctrine']['dbal'])  && $container->get('config')['kipchak.doctrine']['dbal']['enabled']) {
    $dbalConfig = $container->get('config')['kipchak.doctrine']['dbal'];
    $dbalConnections = $dbalConfig['connections'];
    foreach ($dbalConnections as $dbalConnectionName => $dbalConnection) {
        $container->set('database.doctrine.dbal.' . $dbalConnectionName, function (ContainerInterface $c) use ($dbalConnectionName, $dbalConnection): Connection {
            return DriverManager::getConnection(
                [
                    'dbname'    => $dbalConnection['dbname'],
                    'user'      =>  $dbalConnection['user'],
                    'password'  =>  $dbalConnection['password'],
                    'host'      =>  $dbalConnection['host'],
                    'driver'    =>  $dbalConnection['driver'],
                    'port'      => $dbalConnection['port'],
                    'charset'   => $dbalConnection['charset']
                ]
            );
        });
    }
}

// Create all Doctrine Entity Managers
if (isset($container->get('config')['kipchak.doctrine']['orm'])  && $container->get('config')['kipchak.doctrine']['orm']['enabled']) {
    $ormConfig = $container->get('config')['kipchak.doctrine']['orm'];
    $entityManagers = $ormConfig['entity_managers'];
    /**
     * @var $log Logger
     */
    $log = $container->get('logger');
    foreach ($entityManagers as $emName => $emConnection) {
        $container->set('database.doctrine.entitymanager.' . $emName, function (ContainerInterface $c) use ($emName, $emConnection, $log): EntityManager {
            if (!is_dir($emConnection['cache_config']['file']['dir'])) {
                $log->debug('Doctrine ORM: Creating Cache Directory '. $emConnection['cache_config']['file']['dir'] . '.');
                mkdir ($emConnection['cache_config']['file']['dir'], 0777, true);
            }

            if ($emConnection['dev_mode']) {
                $log->debug('Doctrine ORM: Dev mode detected. Using Array Adapter for caching.');
                $cache = new ArrayAdapter();
            } elseif (!$emConnection['dev_mode'] && $emConnection['cache']['enabled']) {
                $cacheStore = $emConnection['cache']['store'];
                $log->debug('Doctrine ORM: Production mode detected. Cache store is set to ' . $cacheStore . '.');
                if ($cacheStore == 'file') {
                    $cache = new FilesystemAdapter(namespace: $emName,  directory: $emConnection['cache_config']['file']['dir']);
                } elseif ($cacheStore == 'memcached') {
                    $log->debug('Doctrine ORM: Memcached pool name is set to '. $cacheStore . '.');
                    $connectionPool = $emConnection['cache_config']['memcached']['pool'];
                    $cache = $c->get('cache.memcached.' . $connectionPool);
                } else {
                    // Default to file store anyway. We'll have this separately incase we add more, like Redis.
                    $cache = new FilesystemAdapter(namespace: $emName, directory: $emConnection['cache_config']['file']['dir']);
                }
            }

            if ($emConnection['metadata_format'] === 'attributes') {
                $emConfig = ORMSetup::createAttributeMetadataConfiguration(
                    $emConnection['metadata_dirs'],
                    $emConnection['dev_mode'],
                    null,
                    $cache
                );
            }

            if ($emConnection['metadata_format'] === 'annotations') {
                $emConfig = ORMSetup::createAnnotationMetadataConfiguration(
                    $emConnection['metadata_dirs'],
                    $emConnection['dev_mode'],
                    null,
                    $cache
                );
            }

            return EntityManager::create($c->get('config')['kipchak.doctrine']['dbal']['connections'][$emConnection['connection']], $emConfig);

        });
    }
}
