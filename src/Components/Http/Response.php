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

    public static function json(ResponseInterface $response, mixed $data, int $code, bool $cache = false, int $cacheTTL = 3600, array $cacheControlHeaders = []): ResponseInterface
    {
        $json = json_encode(self::build($data, $code));
        $response->getBody()->write($json);

        if (empty($cacheControlHeaders)) {
            $headersString = '';
        } else {
            $headersString = implode(',', $cacheControlHeaders) . ',';
        }
        if ($cache) {
            return $response->withHeader('Content-Type', 'application/json')
                ->withAddedHeader('Cache-Control', $headersString . 'max-age=' . $cacheTTL)
                ->withAddedHeader('ETag', md5($json))
                ->withAddedHeader('X-Powered-By', 'Kipchak by Mamluk')
                ->withStatus($code);
        }

        return $response->withHeader('Content-Type', 'application/json')
            ->withAddedHeader('X-Powered-By', 'Kipchak by Mamluk')
            ->withStatus($code);
    }

    public static function redirect(ResponseInterface $response, string $url, int $code = 302): ResponseInterface
    {
        return $response->withStatus($code)
            ->withHeader('Location', $url)
            ->withAddedHeader('X-Powered-By', 'Kipchak by Mamluk');
    }
}