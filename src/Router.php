<?php

declare(strict_types=1);

namespace Quillstack\Router;

use Quillstack\HttpRequest\HttpRequest;
use Quillstack\Router\Exceptions\RouteNameNotSetException;
use Quillstack\Router\RouteTree\RouteTreeBuilder;

class Router implements RouterInterface
{
    /**
     * All registered routes, keyed by `METHOD /path`.
     */
    private array $routes = [];

    /**
     * Routes registered by the last call, so `name()` can be chained onto them.
     *
     * @var RouteInterface[]
     */
    private array $currentRoutes = [];

    private array $tree = [];

    public RouteTreeBuilder $routeTreeBuilder;

    /**
     * {@inheritDoc}
     */
    public function map(string $method, string $path, string $controller): RouterInterface
    {
        $method = strtoupper($method);
        $path = self::normalisePath($path);
        $route = new Route($method, $path, $controller);

        $this->routes[$route->getKey()] = $route;
        $this->currentRoutes = [$route];
        $this->routeTreeBuilder->build($this->tree, $route, $method, $path);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function match(array $methods, string $path, string $controller): RouterInterface
    {
        $routes = [];

        foreach ($methods as $method) {
            $this->map($method, $path, $controller);
            $routes[] = $this->currentRoutes[0];
        }

        $this->currentRoutes = $routes;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function any(string $path, string $controller): RouterInterface
    {
        return $this->match(HttpRequest::AVAILABLE_METHODS, $path, $controller);
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $path, string $controller): RouterInterface
    {
        return $this->map(HttpRequest::METHOD_GET, $path, $controller);
    }

    /**
     * {@inheritDoc}
     */
    public function post(string $path, string $controller): RouterInterface
    {
        return $this->map(HttpRequest::METHOD_POST, $path, $controller);
    }

    /**
     * {@inheritDoc}
     */
    public function put(string $path, string $controller): RouterInterface
    {
        return $this->map(HttpRequest::METHOD_PUT, $path, $controller);
    }

    /**
     * {@inheritDoc}
     */
    public function patch(string $path, string $controller): RouterInterface
    {
        return $this->map(HttpRequest::METHOD_PATCH, $path, $controller);
    }

    /**
     * {@inheritDoc}
     */
    public function delete(string $path, string $controller): RouterInterface
    {
        return $this->map(HttpRequest::METHOD_DELETE, $path, $controller);
    }

    /**
     * {@inheritDoc}
     */
    public function options(string $path, string $controller): RouterInterface
    {
        return $this->map(HttpRequest::METHOD_OPTIONS, $path, $controller);
    }

    /**
     * {@inheritDoc}
     */
    public function head(string $path, string $controller): RouterInterface
    {
        return $this->map(HttpRequest::METHOD_HEAD, $path, $controller);
    }

    /**
     * {@inheritDoc}
     */
    public function name(string $name): RouterInterface
    {
        if ($this->currentRoutes === []) {
            throw new RouteNameNotSetException('No route to name, call a routing method first');
        }

        foreach ($this->currentRoutes as $route) {
            $route->setName($name);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * {@inheritDoc}
     */
    public function getRoute(string $name): ?RouteInterface
    {
        foreach ($this->routes as $route) {
            if ($route->getName() === $name) {
                return $route;
            }
        }

        return null;
    }

    public function getTree(): array
    {
        return $this->tree;
    }

    /**
     * Brings a path to the form used as a routing key: a leading slash, and no trailing one.
     */
    public static function normalisePath(string $path): string
    {
        $path = '/' . trim($path, '/');

        return $path === '/' ? '/' : rtrim($path, '/');
    }
}
