<?php

declare(strict_types=1);

namespace Quillstack\Router;

use Psr\Http\Message\ServerRequestInterface;
use Quillstack\Router\Routes\NotFoundRoute;
use Quillstack\Router\RouteTree\RouteTreeFinder;

final class Dispatcher implements DispatcherInterface
{
    /**
     * @var Router
     */
    public Router $router;

    /**
     * @var RouteTreeFinder
     */
    public RouteTreeFinder $routeTreeFinder;

    /**
     * @var string
     */
    public string $routeNotFoundController = '';

    /**
     * {@inheritDoc}
     */
    public function dispatch(ServerRequestInterface $serverRequest): ?RouteInterface
    {
        $exactMatch = $this->findExactMatch($serverRequest);

        return $exactMatch ?? $this->findWildcardMatch($serverRequest);
    }

    /**
     * @param ServerRequestInterface $serverRequest
     *
     * @return Route|null
     */
    private function findExactMatch(ServerRequestInterface $serverRequest): ?RouteInterface
    {
        $routes = $this->router->getRoutes();
        $key = $this->getKeyForExactMatch($serverRequest);

        return $routes[$key] ?? null;
    }

    /**
     * @param ServerRequestInterface $serverRequest
     *
     * @return Route|null
     */
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

    private function getRouteNotFound()
    {
        return new NotFoundRoute($this->routeNotFoundController);
    }

    /**
     * @param string $key
     *
     * @return array
     */
    private function getBranch(string $key): array
    {
        $branch = explode('/', $key);
        $branch[] = '';

        return $branch;
    }

    /**
     * @param ServerRequestInterface $serverRequest
     *
     * @return string
     */
    private function getKeyForExactMatch(ServerRequestInterface $serverRequest): string
    {
        return $serverRequest->getMethod() . ' ' . $serverRequest->getRequestTarget();
    }

    /**
     * @param ServerRequestInterface $serverRequest
     *
     * @return string
     */
    private function getKeyForWildcardMatch(ServerRequestInterface $serverRequest): string
    {
        return $serverRequest->getMethod() . $serverRequest->getRequestTarget();
    }
}
