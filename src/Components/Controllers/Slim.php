<?php

namespace Meezaan\Microservice\Components\Controllers;

use Monolog\Logger;
use Psr\Container\ContainerInterface;

class Slim
{
    /**
     * @var ContainerInterface 
     */
    protected $container;

    /**
     * @var Logger
     */
    protected $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->logger = $this->container->get('logger');
    }
}