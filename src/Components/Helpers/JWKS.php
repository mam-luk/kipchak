<?php

namespace Mamluk\Kipchak\Components\Helpers;

use Firebase\JWT\JWK;
use Firebase\JWT\JWT;

class JWKS
{
    public static function decode(string $jwt, string $jwksUri): \stdClass
    {
        $jwks = json_decode(file_get_contents($jwksUri), true);

        return JWT::decode($jwt,
            JWK::parseKeySet($jwks)
        );

    }

    public static function hasScopes(string $inToken, array $required): bool
    {
        $in = explode(' ', $inToken);
        foreach ($in as $scope) {
            // Return true even if 1 scope matches.
            if (in_array($scope, $required)) {
                return true;
            }
        }

        return false;
    }
}