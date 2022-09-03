<?php

Use Monolog\Logger;
use Monolog\Level;
Use Monolog\Handler\StreamHandler;
use Psr\Container\ContainerInterface;

/**
 * @var $container ContainerInterface
 */

$container->set('logger', function(ContainerInterface $c): Logger {

    if (isset($c->get('config')['kipchak.api'])) {
        $apiConfig = $c->get('config')['kipchak.api'];
        if (
            isset($apiConfig['loglevel']) &&
            in_array(
                $apiConfig['loglevel'],
                [
                    Level::Info,
                    Level::Debug,
                    Level::Warning,
                    Level::Alert,
                    Level::Critical,
                    Level::Emergency,
                    Level::Error
                ])) {
            $logLevel = $apiConfig['loglevel'];
        } else {
            $logLevel = $apiConfig['debug'] === true ? Level::Debug : Level::Info;
        }

        $log = new Logger($apiConfig['name']);
        $log->pushHandler(new StreamHandler('php://stdout', $logLevel));

        return $log;
    }
});