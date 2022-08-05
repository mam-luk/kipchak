<?php


namespace Mamluk\Kipchak\Components\Middlewares;

use Mamluk\Kipchak\Components\Http;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use Mamluk\Kipchak\Components\Helpers\JWKS;
use Symfony\Contracts\Cache\ItemInterface;
use Monolog\Logger;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use function Mamluk\Kipchak\config;

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
        $apiConfig = $this->container->get('config')['api'];

        if (isset($request->getHeader('Authorization')[0])) {
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

                    $this->log->debug('Using a cache contract to get the JWKS key...');

                    $jwks = $this->cache->get('jwks', function (ItemInterface $item) use ($jwksUri) {
                        $item->expiresAfter(86400);
                        $this->log->debug('Cache miss. Getting JWKS key from the URL');
                        $jwks = json_decode(file_get_contents($jwksUri), true);

                        return $jwks;
                    });

                    $token = JWKS::decode($jwt, $jwks);

                    if (!JWKS::hasScopes($token->scope, $apiConfig['auth']['jwks']['scopes'])) {
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