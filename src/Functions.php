<?php

namespace Mamluk\Kipchak;

/**
 * @param string $file name of config file in the config folder of the Kipchak API
 * @return array
 */
function config(string $file): array
{
    $configDir = realpath(__DIR__ . '/../../../../config/');
    $configFile = $configDir . '/' . $file . '.php';
    if (file_exists($configFile)) {
        $config = include_once($configFile);

        return $config;
    }

    return [];
}

/**
 * @param string $name Name of the environment variable to return
 * @param string $default This will be returned if the environment variable does not exist
 * @return string
 */
function env(string $name, string $default): string
{
    if (getenv($name)) {
        return getenv($name);
    }

    return $default;
}


