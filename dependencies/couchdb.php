<?php

use Psr\Container\ContainerInterface;
use Mamluk\Couch\Database\Client;

/**
 * @var $container ContainerInterface
 */

if (isset($container->get('config')['kipchak.couchdb']['connections']) && $container->get('config')['kipchak.couchdb']['enabled']) {
    $connections = $container->get('config')['kipchak.couchdb']['connections'];
    $logger = $container->get('logger');
    $httpClient = $container->get('http.client');
    foreach($connections as $connectionName => $connectionDetails) {
        $container->set('database.couchdb.' . $connectionName, function (ContainerInterface $c) use ($connectionDetails, $httpClient, $logger): CouchDB {
            return new Client($connectionDetails['username'], $connectionDetails['password'],
                $connectionDetails['database'], $connectionDetails['host'], $logger,
                $httpClient, $connectionDetails['port']);
        });
    }
}
