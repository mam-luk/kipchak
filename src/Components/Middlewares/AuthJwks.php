<?php


namespace Mamluk\Kipchak\Components\Middlewares;

use Mamluk\Kipchak\Components\Http;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use Mamluk\Kipchak\Components\Auth\JWKS;
use Symfony\Contracts\Cache\ItemInterface;
use Monolog\Logger;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class AuthJwks
{
    public ContainerInterface $container;

    private Logger $log;

    private FilesystemAdapter $cache;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->cache = $container->get('cache_file');
        $this->log = $container->get('logger');

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

        if (isset($request->getHeader('Authorization')[0])) {
            $authHeader = $request->getHeader('Authorization')[0];
            if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
                // No Bearer token found.
                return Http\Response::json($response,
                    'No Bearer token found in the Authorization header',
                    401
                );
            } else {
                try {
                    // Bearer token found
                    $jwt = $matches[1];
                    // The URI for the JWKS you wish to cache the results from
                    $jwksUri = $authConfig['jwks']['jwksUri'];

                    $this->log->debug('Using a cache contract to get the JWKS key...');

                    $jwks = $this->cache->get('jwks', function (ItemInterface $item) use ($jwksUri) {
                        $item->expiresAfter(86400);
                        $this->log->debug('Cache miss. Getting JWKS key from the URL');
                        $jwks = json_decode(file_get_contents($jwksUri), true);

                        return $jwks;
                    });

                    $token = JWKS::decode($jwt, $jwks);

                    if ($authConfig['jwks']['validate_scopes']) {
                        if (!JWKS::hasScopes($token->scope, $authConfig['jwks']['scopes'])) {
                            return Http\Response::json($response,
                                'Missing required scope(s)',
                                401
                            );
                        }
                    }

                    // Add the token to the container so the route can access it if needed
                    $this->container->set('token', function(ContainerInterface $c) use ($token) {
                        return $token;
                    });

                    // If we got this far, we can let the token through
                    $response = $handler->handle($request);
                    
                    return $response;

                } catch (\Exception $e) {
                    return Http\Response::json($response,
                        'Unable to decode token. ' . $e->getMessage(),
                        401
                    );
                }
            }
        } else {
            // No Authorization header or JWT auth is disabled in config, so do nothing.
            return Http\Response::json($response,
                'No token found',
                401
            );
        }
    }

}