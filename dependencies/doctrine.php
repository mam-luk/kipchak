<?php

use Psr\Container\ContainerInterface;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Doctrine\ORM\ORMSetup;
use Doctrine\DBAL\Connection;

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
    foreach ($entityManagers as $emName => $emConnection) {
        $container->set('database.doctrine.entitymanager.' . $emName, function (ContainerInterface $c) use ($emName, $emConnection): EntityManager {
            if ($emConnection['dev_mode']) {
                $cache = new ArrayAdapter();
            } elseif (!$emConnection['dev_mode'] && $emConnection['cache']['enabled']) {
                $cacheStore = $emConnection['cache']['store'];
                if ($cacheStore == 'file') {
                    $cache = new FilesystemAdapter(directory: $emConnection['cache_config']['file']['dir']);
                } elseif ($cacheStore == 'memcached') {
                    $connectionPool = $emConnection['cache_config']['memcached']['pool'];
                    $cache = $c->get('cache.memcached.' . $connectionPool);
                } else {
                    // Default to file store anyway. We'll have this separately incase we add more, like Redis.
                    $cache = new FilesystemAdapter(directory: $emConnection['cache_config']['file']['dir']);
                }
            }

            if ($emConnection['metadata_format'] === 'attributes') {
                $emConfig = ORMSetup::createAttributeMetadataConfiguration(
                    $emConnection['metadata_dirs'],
                    $emConnection['dev_mode'],
                    '/tmp/' . $emName,
                    $cache
                );
            }

            if ($emConnection['metadata_format'] === 'annotations') {
                $emConfig = ORMSetup::createAnnotationMetadataConfiguration(
                    $emConnection['metadata_dirs'],
                    $emConnection['dev_mode'],
                    '/tmp/' . $emName,
                    $cache
                );
            }

            return EntityManager::create($c->get('config')['kipchak.doctrine']['dbal']['connections'][$emConnection['connection']], $emConfig);

        });
    }
}
