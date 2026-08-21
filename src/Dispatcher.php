<?php

declare(strict_types=1);

namespace Quillstack\Router;

use Psr\Http\Message\ServerRequestInterface;
use Quillstack\Router\Routes\NotFoundRoute;
use Quillstack\Router\RouteTree\RouteTreeFinder;

class Dispatcher implements DispatcherInterface
{
    public Router $router;
    public RouteTreeFinder $routeTreeFinder;
    public string $routeNotFoundController = '';

    /**
     * {@inheritDoc}
     */
    public function dispatch(ServerRequestInterface $serverRequest): RouteInterface
    {
        $method = strtoupper($serverRequest->getMethod());
        $path = $this->getPath($serverRequest);

        return $this->findExactMatch($method, $path)
            ?? $this->findParameterMatch($method, $path)
            ?? new NotFoundRoute($this->routeNotFoundController);
    }

    private function findExactMatch(string $method, string $path): ?RouteInterface
    {
        $routes = $this->router->getRoutes();

        return $routes["{$method} {$path}"] ?? null;
    }

    private function findParameterMatch(string $method, string $path): ?RouteInterface
    {
        return $this->routeTreeFinder->findRoute(
            $this->router->getTree(),
            $this->getBranch($method, $path)
        );
    }

    /**
     * The path used for routing, taken from the URI so that a query string is left out.
     */
    private function getPath(ServerRequestInterface $serverRequest): string
    {
        return Router::normalisePath(
            $serverRequest->getUri()->getPath()
        );
    }

    /**
     * Splits the request into the segments walked through the route tree. The closing empty
     * segment marks the end of the branch, where the route itself is stored.
     */
    private function getBranch(string $method, string $path): array
    {
        $branch = explode('/', trim($method . $path, '/'));
        $branch[] = '';

        return $branch;
    }
}
