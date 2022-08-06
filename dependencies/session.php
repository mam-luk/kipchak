<?php

use SlimSession\Helper;

$csess = $container->get('config')['sessions'];

if ($csess['enabled']) {
    $container->set('session', function () {
        return new Helper();
    });
}

