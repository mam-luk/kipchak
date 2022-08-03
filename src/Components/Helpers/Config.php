<?php
namespace Meezaan\Microservice\Components\Helpers;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class Config
{
    public $config;

    public function __construct()
    {
        $configDir = realpath(__DIR__ . '/../../../../../config');

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($configDir));
        $configs = array_keys(array_filter(iterator_to_array($iterator), function($file) {
            return $file->isFile();
        }));

        foreach ($configs as $configFile) {
            if (strpos($configFile, '.php') !== false) {
                require_once(realpath($configFile));
            }
        }

        $this->config = $config;

    }

    public function get(string $name = null): mixed
    {
        if ($name === null) {
            return $this->config;
        }

        if ($name !== null && isset($this->config[$name])) {
            return $this->config[$name];
        }

        // Could not find config param, return null
        return null;
    }

}
