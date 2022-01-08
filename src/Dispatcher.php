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
    public function dispatch(ServerRequestInterface $serverRequest): ?RouteInterface
    {
        $exactMatch = $this->findExactMatch($serverRequest);

        return $exactMatch ?? $this->findWildcardMatch($serverRequest);
    }

    private function findExactMatch(ServerRequestInterface $serverRequest): ?RouteInterface
    {
        $routes = $this->router->getRoutes();
        $key = $this->getKeyForExactMatch($serverRequest);

        return $routes[$key] ?? null;
    }

    private function findWildcardMatch(ServerRequestInterface $serverRequest): ?RouteInterface
    {
        $key = $this->getKeyForWildcardMatch($serverRequest);
        $tree = $this->router->getTree();
        $route = $this->routeTreeFinder->findRoute(
            $tree,
            $this->getBranch($key)
        );

        return $route ?? $this->getRouteNotFound();
    }

    private function getRouteNotFound(): NotFoundRoute
    {
        return new NotFoundRoute($this->routeNotFoundController);
    }

    private function getBranch(string $key): array
    {
        $branch = explode('/', $key);
        $branch[] = '';

        return $branch;
    }

    private function getKeyForExactMatch(ServerRequestInterface $serverRequest): string
    {
        return $serverRequest->getMethod() . ' ' . $serverRequest->getRequestTarget();
    }

    private function getKeyForWildcardMatch(ServerRequestInterface $serverRequest): string
    {
        return $serverRequest->getMethod() . $serverRequest->getRequestTarget();
    }
}
