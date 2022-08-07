<?php

use SlimSession\Helper;

$csess = $container->get('config')['kipchak_sessions'];

if ($csess['enabled']) {
    $container->set('session', function () {
        return new Helper();
    });
}

