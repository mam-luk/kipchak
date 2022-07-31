<?php

namespace Meezaan\Microservice\Components\Controllers;

use Psr\Container\ContainerInterface;

class Slim
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}