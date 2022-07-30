<?php
namespace Meezaan\Microservice\Helpers;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class Config
{
    public $config;

    public function __construct()
    {
        $configDir = realpath(__DIR__ . '/../../../../../config')  ;

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

    public function get(string $name)
    {
        return $this->config[$name];
    }
}
