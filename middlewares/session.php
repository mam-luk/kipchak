<?php

use Slim\Middleware\Session;
use Mamluk\Couch\Session\Handler;

if (isset($container->get('config')['kipchak.sessions'])) {
    $csess = $container->get('config')['kipchak.sessions'];

    if ($csess['enabled']) {
        if ($csess['store'] == 'couchdb') {
            $couchConfig = $container->get('config')['kipchak.couchdb']['connections'][$csess['store_config']['couchdb']['connection']];

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
                        'path' => $csess['cookie_options']['path'],
                        'domain' => $csess['cookie_options']['domain'],
                        'secure' => $csess['cookie_options']['secure'],
                        'httponly' => $csess['cookie_options']['httponly'],
                        'samesite' => $csess['cookie_options']['samesite'],
                        'handler' =>
                            new Handler(
                                $csess['lifetime'],
                                $couchConfig['username'],
                                $couchConfig['password'],
                                $csess['store_config']['couchdb']['database'],
                                $couchConfig['host'],
                                $couchConfig['port']
                            )
                    ]
                )
            );
        } elseif ($csess['store'] == 'memcached') {
            $servers = [];
            $memachedPool = $container->get('config')['kipchak.memcached']['pools'][$csess['store_config']['memcached']['pool']];
            foreach ($memachedPool as $server) {
                $servers[] = $server['host'] . ':' . $server['port'];
            }

            ini_set('session.save_handler', 'memcached');
            $serverString = implode(',', $servers);
            ini_set('session.save_path', $serverString);

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
                            ]
                    ]
                )
            );

        }
    }
}