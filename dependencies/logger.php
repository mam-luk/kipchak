<?php

Use Monolog\Logger;
use Monolog\Level;
Use Monolog\Handler\StreamHandler;
use Psr\Container\ContainerInterface;

$container->set('logger', function(ContainerInterface $c) {
$config = $c->get('config');
$debug = $config->get('api')['debug'];
$logLevel = $debug === true ? Level::Debug : Level::Info;
$log = new Logger('Api');
$log->pushHandler( new StreamHandler('php://stdout', $logLevel));

return $log;
});