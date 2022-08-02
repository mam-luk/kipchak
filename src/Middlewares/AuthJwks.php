<?php


namespace Meezaan\Microservice\Middlewares;

use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Meezaan\Microservice\Components\Http;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RequestHandler;
use ServerRequest;
use Slim\Psr7\Response;
use Meezaan\Microservice\Helpers\JWKS;

class AuthJwks
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
        $config = $this->container->get('config');
        $apiConfig = $config->get('api');

        if ($apiConfig['auth']['jwks']['enabled'] && isset($request->getHeader('Authorization')[0])) {
            $response = new Response();
            $authHeader = $request->getHeader('Authorization')[0];
            if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
                // No Bearer token found.
                return Http\Response::json($response,
                    'No Bearer token found in the Authorization header',
                    403
                );
            } else {
                try {
                    // Bearer token found

                    $jwt = $matches[1];
                    // The URI for the JWKS you wish to cache the results from
                    $jwksUri = $apiConfig['auth']['jwks']['jwksUri'];

                    $token = JWKS::decode($jwt, $jwksUri);

                    if (!JWKS::hasScopes($token->scopes, $apiConfig['auth']['jwks']['scopes'])) {
                        return Http\Response::json($response,
                            'Missing required scope(s)',
                            403
                        );
                    }

                    // If we got this far, we can let the token through
                    $response = $handler->handle($request);
                    return $response;

                } catch (\Exception $e) {
                    return Http\Response::json($response,
                        'Unable to decode token. ' . $e->getMessage(),
                        403
                    );
                }
            }
        } else {
            // No Authorization header or JWT auth is disabled in config, so do nothing.
            $response = $handler->handle($request);
            return $response;
        }
    }

}