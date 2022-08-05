<?php
namespace Mamluk\Kipchak;

use Slim\Factory\AppFactory;
use Slim\App;
use DI\Container;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class Api
{
    public static function boot(): App
    {
        // Create DI Container and App
        $container = new Container();
        AppFactory::setContainer($container);
        $app = AppFactory::create();
        $app->addRoutingMiddleware();
        $container = $app->getContainer();

        self::loadDependencies($app, $container);
        self::loadMiddlewares($app, $container);
        self::loadRoutes($app, $container);

        return $app;
    }

    public static function loadDependencies(App $app): void
    {
        $container = $app->getContainer();
        // Load static functions
        require_once(realpath(__DIR__ . '/Functions.php'));
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
    }

    public static function loadMiddlewares(App $app): void
    {
        $container = $app->getContainer();
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
    }

    public static function loadRoutes(App $app): void
    {
        $container = $app->getContainer();
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