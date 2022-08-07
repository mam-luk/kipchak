<?php


namespace Mamluk\Kipchak\Components\Middlewares;

use Mamluk\Kipchak\Components\Http;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class AuthKey
{
    public $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

    }

    /**
     * JWKS Authentication middleware invokable class
     *
     * @param ServerRequestInterface $request PSR-7 request
     * @param RequestHandlerInterface $handler PSR-15 request handler
     *
     * @return Response
     */
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): Response
    {
        $authConfig = $this->container->get('config')['kipchak_auth'];


            $response = new Response();
            $key = isset($request->getHeader('x-api-key')[0]) ?
                $request->getHeader('x-api-key')[0] :
                Http\Request::getQueryParam($request, 'key');

            if (isset($authConfig['authorised_keys'][$key])) {
                // Key matched!
                $response = $handler->handle($request);

                return $response;
            }

        return Http\Response::json($response,
            'Missing or invalid key',
            401
        );

    }
}