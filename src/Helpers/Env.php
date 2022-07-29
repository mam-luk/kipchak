<?php
namespace Meezaan\Microservice\Helpers;

class Env
{
    public static function get(string $name, string $default)
    {
        if (getenv($name)) {
            return getenv($name);
        }

        return null;
    }
}
