<?php
namespace Mamluk\Kipchak\Components\Http;

use Psr\Http\Message\ServerRequestInterface;

class Request
{
    public static function getQueryParam(ServerRequestInterface $request, string $name): mixed
    {
        $params = $request->getQueryParams();
        if (isset($params[$name])) {
            return $params[$name];
        }

        return null;

    }
}