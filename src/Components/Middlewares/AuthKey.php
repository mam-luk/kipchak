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
        $authConfig = $this->container->get('config')['kipchak.auth'];


        $response = new Response();

        if (
            (isset($authConfig['jwks']['ignore_options']) && $request->getMethod() === 'OPTIONS' && $authConfig['jwks']['ignore_options']) ||
            (isset($authConfig['jwks']['ignore_paths']) && in_array($request->getUri()->getPath(), $authConfig['jwks']['ignore_paths']))
        ) {
            $response = $handler->handle($request);

            return $response;
        }

        $key = isset($request->getHeader('apikey')[0]) ?
            $request->getHeader('apikey')[0] :
            Http\Request::getQueryParam($request, 'apikey');

        if (isset($authConfig['key']['authorised_keys'][$key])) {
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