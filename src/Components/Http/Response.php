<?php
namespace x7x\Components\Http;

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

    public static function slimJson(ResponseInterface $response, mixed $data, int $code): ResponseInterface
    {
        $response->getBody()->write(
                json_encode(self::build($data, $code))
        );

        return $response->withStatus($code);
    }
}