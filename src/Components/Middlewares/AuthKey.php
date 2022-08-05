<?php


namespace Mamluk\Kipchak\Components\Middlewares;

use Mamluk\Kipchak\Components\Http;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RequestHandler;
use ServerRequest;
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
        $apiConfig = $this->container->get('config')['api'];


            $response = new Response();
            $key = isset($request->getHeader('x-api-key')[0]) ?
                $request->getHeader('x-api-key')[0] :
                Http\Request::getQueryParam($request, 'key');

            if (!in_array($key, $apiConfig['auth']['key']['authorised_keys'])) {
                return Http\Response::json($response,
                    'Missing or invalid key',
                    403
                );
            }

            // If we got this far, we can let the token through
            $response = $handler->handle($request);
            return $response;
    }
}