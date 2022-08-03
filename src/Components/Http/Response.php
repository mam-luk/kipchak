<?php
namespace Meezaan\Microservice\Components\Http;

use Psr\Http\Message\ResponseInterface;

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
        $response->getBody()->write(
                json_encode(self::build($data, $code))
        );

        if ($cache) {
            return $response
                ->withAddedHeader('Content-Type', 'application/json')
                ->withAddedHeader('Cache-Control', 'public, must-revalidate, max-age=' . $cacheTTL)
                ->withStatus($code);
        }

        return $response->withHeader('Content-Type', 'application/json')
            ->withStatus($code);

    }
}