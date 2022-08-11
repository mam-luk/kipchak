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

    public static function json(ResponseInterface $response, mixed $data, int $code, bool $cache = false, int $cacheTTL = 3600): ResponseInterface
    {
        $json = json_encode(self::build($data, $code));
        $response->getBody()->write($json);

        if ($cache) {
            return $response->withHeader('Content-Type', 'application/json')
                ->withAddedHeader('Cache-Control', 'public, must-revalidate, max-age=' . $cacheTTL)
                ->withAddedHeader('ETag', md5($json))
                ->withStatus($code);
        }

        return $response->withHeader('Content-Type', 'application/json')
            ->withStatus($code);
        }
}