<?php

use SlimSession\Helper;

if (isset($container->get('config')['kipchak_sessions'])) {
    $csess = $container->get('config')['kipchak_sessions'];

    if ($csess['enabled']) {
        $container->set('session', function () {
            return new Helper();
        });
    }
}
