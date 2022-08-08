<?php

use Psr\Container\ContainerInterface;
use Mamluk\Kipchak\Components\Database\Clients\CouchDB;

if (isset($container->get('config')['couchdb']['connections']) && $container->get('config')['couchdb']['enabled']) {
    $connections = $container->get('config')['couchdb']['connections'];
    $logger = $container->get('logger');
    foreach($connections as $connectionName => $connectionDetails) {
        $container->set('database.couchdb.' . $connectionName , function (ContainerInterface $c) use ($connectionDetails, $logger) {
            return new CouchDB($connectionDetails['username'], $connectionDetails['password'],
                $connectionDetails['database'], $connectionDetails['host'], $connectionDetails['port'], $logger);
        });
    }

}
