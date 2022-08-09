<?php

use Psr\Container\ContainerInterface;
use SlimSession\Helper;

if (isset($container->get('config')['kipchak.sessions'])) {
    $csess = $container->get('config')['kipchak.sessions'];

    if ($csess['enabled']) {
        $container->set('session', function (ContainerInterface $c): Helper {
            return new Helper();
        });
    }
}
