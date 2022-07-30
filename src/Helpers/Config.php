<?php
namespace Meezaan\Microservice\Helpers;

class Config
{
    public static function get(string $name)
    {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(realpath(__DIR__) . '/../../../../config'));
        $configs = array_keys(array_filter(iterator_to_array($iterator), function($file) {
            return $file->isFile();
        }));

        foreach ($configs as $configFile) {
            if (strpos($configFile, '.php') !== false) {
                require_once(realpath($configFile));
            }
        }

        var_dump($config);
    }
}
