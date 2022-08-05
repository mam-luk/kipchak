<?php

Use Monolog\Logger;
use Monolog\Level;
Use Monolog\Handler\StreamHandler;
use Psr\Container\ContainerInterface;

$container->set('logger', function(ContainerInterface $c) {

$apiConfig = $c->get('config')['api'];
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

$log = new Logger('Api');
$log->pushHandler( new StreamHandler('php://stdout', $logLevel));

return $log;
});