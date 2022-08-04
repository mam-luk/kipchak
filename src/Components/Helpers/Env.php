<?php
namespace Mamluk\Kipchak\Components\Helpers;

class Env
{
    public static function get(string $name, string $default): mixed
    {
        if (getenv($name)) {
            return getenv($name);
        }

        return null;
    }
}
