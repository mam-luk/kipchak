<?php
namespace Mamluk\Kipchak\Components\Http;

use Psr\Http\Message\ResponseInterface;
use Slim\HttpCache\CacheProvider;

class Response
{
    public static function build(mixed $data, int $code): array
    {
        return
            [
                'code' => $code,
                'status' => Codes::getCode($code),
                'data' => $data
            ];
    }

    public static function json(ResponseInterface $response, mixed $data, int $code, bool $cache = false, int $cacheTTL = 3600, CacheProvider $provider = new CacheProvider()): ResponseInterface
    {
        $json = json_encode(self::build($data, $code));
        $response->getBody()->write($json);

        if ($cache) {
            $response = $provider->withExpires($response, $cacheTTL);
            $response = $provider->withEtag($response, $json);
        }

        return $response->withHeader('Content-Type', 'application/json')
            ->withStatus($code);
    }
}