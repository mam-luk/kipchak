<?php

use Slim\Middleware\Session;

$csess = $container->get('config')['sessions'];

if ($csess['enabled'] && $csess['store'] == 'couchdb') {

    $cdb = $container->get('config')['couchdb']['default'];

    $app->add(
        new Session(
            [
                'name' => $csess['name'],
                'autorefresh' => true,
                'lifetime' => $csess['lifetime'],
                'ini_settings' =>
                    [
                        'session.gc_divisor' => 1,
                        'session.gc_probability' => 1,
                        'session.gc_maxlifetime' => 30 * 24 * 60 * 60,
                    ],
                'handler' =>
                    new \Mamluk\Kipchak\Components\Helpers\CouchDBSessionHandler(
                        $lifetime,
                        $cdb['username'],
                        $cdb['password'],
                        'api_sessions',
                        $cdb['host'],
                        $cdb['port']
                    )
            ]
        )
    );
}