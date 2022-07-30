<?php
namespace Meezaan\Microservice\Helpers;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class Api
{
    public static function boot()
    {
        /** Load all the dependency files in the /routes folder of this project **/
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(realpath(__DIR__ . '/../dependencies')));
        $dependencies = array_keys(array_filter(iterator_to_array($iterator), function($file) {
            return $file->isFile();
        }));

        foreach ($dependencies as $dependency) {
            if (strpos($dependency, '.php') !== false) {
                require_once(realpath($dependency));
            }
        }

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(realpath(__DIR__ . '/../../../../dependencies')));
        $dependencies = array_keys(array_filter(iterator_to_array($iterator), function($file) {
            return $file->isFile();
        }));

        foreach ($dependencies as $dependency) {
            if (strpos($dependency, '.php') !== false) {
                require_once(realpath($dependency));
            }
        }
        /***/

        /** Load all the middleware files in the /routes folder of this project **/
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(realpath(__DIR__ . '/../middlewares')));
        $middlewares = array_keys(array_filter(iterator_to_array($iterator), function($file) {
            return $file->isFile();
        }));

        foreach ($middlewares as $middleware) {
            if (strpos($middleware, '.php') !== false) {
                require_once(realpath($middleware));
            }
        }

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(realpath(__DIR__ . '/../../../../middlewares')));
        $middlewares = array_keys(array_filter(iterator_to_array($iterator), function($file) {
            return $file->isFile();
        }));

        foreach ($middlewares as $middleware) {
            if (strpos($middleware, '.php') !== false) {
                require_once(realpath($middleware));
            }
        }
        /***/




        /** Load all the routes files in the /routes folder of this project **/
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(realpath(__DIR__ . '/../../../../routes')));
        $routes = array_keys(array_filter(iterator_to_array($iterator), function($file) {
            return $file->isFile();
        }));

        foreach ($routes as $route) {
            if (strpos($route, '.php') !== false) {
                require_once(realpath($route));
            }
        }
        /***/
    }
}