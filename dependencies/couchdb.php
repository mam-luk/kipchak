<?php

use Psr\Container\ContainerInterface;
use Mamluk\Kipchak\Components\Database\Clients\CouchDB;

if (isset($container->get('config')['kipchak.couchdb']['connections']) && $container->get('config')['kipchak.couchdb']['enabled']) {
    $connections = $container->get('config')['kipchak.couchdb']['connections'];
    $logger = $container->get('logger');
    $httpClient = $container->get('http.client');
    foreach($connections as $connectionName => $connectionDetails) {
        $container->set('database.couchdb.' . $connectionName , function (ContainerInterface $c) use ($connectionDetails, $httpClient, $logger) {
            return new CouchDB($connectionDetails['username'], $connectionDetails['password'],
                $connectionDetails['database'], $connectionDetails['host'], $connectionDetails['port'], $httpClient, $logger);
        });
    }

}
