<?php

declare(strict_types=1);

namespace Quillstack\Router;

use Psr\Http\Message\ServerRequestInterface;
use Quillstack\HttpRequest\HttpRequest;
use Quillstack\Router\Routes\MethodNotAllowedRoute;
use Quillstack\Router\Routes\NotFoundRoute;
use Quillstack\Router\RouteTree\RouteTreeFinder;

class Dispatcher implements DispatcherInterface
{
    private readonly RouteTreeFinder $routeTreeFinder;

    /**
     * The finder needs nothing either, so the usual case is `new Dispatcher($router)`.
     */
    public function __construct(
        private readonly Router $router,
        ?RouteTreeFinder $routeTreeFinder = null
    ) {
        $this->routeTreeFinder = $routeTreeFinder ?? new RouteTreeFinder();
    }

    /**
     * {@inheritDoc}
     */
    public function dispatch(ServerRequestInterface $serverRequest): RouteInterface
    {
        $method = strtoupper($serverRequest->getMethod());
        $path = $this->getPath($serverRequest);

        $route = $this->findRoute($method, $path);

        if ($route !== null) {
            return $route;
        }

        // The path may well be registered, just not for this method. Saying so is the
        // difference between `there is no such thing` and `not that way`.
        $allowed = $this->methodsAllowedFor($path, $method);

        return $allowed === []
            ? new NotFoundRoute()
            : new MethodNotAllowedRoute($allowed);
    }

    private function findRoute(string $method, string $path): ?RouteInterface
    {
        $route = $this->findExactMatch($method, $path)
            ?? $this->findParameterMatch($method, $path);

        if ($route !== null || $method !== HttpRequest::METHOD_HEAD) {
            return $route;
        }

        // A server answering GET answers HEAD: the same route, and a response carrying the
        // headers without the body. Registering `head()` separately still wins, this is only
        // what happens when nothing did.
        return $this->findRoute(HttpRequest::METHOD_GET, $path);
    }

    /**
     * Every method this path answers to, apart from the one that was asked for.
     *
     * @return string[]
     */
    private function methodsAllowedFor(string $path, string $asked): array
    {
        $allowed = [];

        foreach (HttpRequest::AVAILABLE_METHODS as $method) {
            if ($method !== $asked && $this->findRoute($method, $path) !== null) {
                $allowed[] = $method;
            }
        }

        return $allowed;
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
     *
     * @return string[]
     */
    private function getBranch(string $method, string $path): array
    {
        $branch = explode('/', trim($method . $path, '/'));
        $branch[] = '';

        return $branch;
    }
}
