<?php

declare(strict_types=1);

namespace Quillstack\Router;

use Quillstack\HttpRequest\HttpRequest;
use Quillstack\Router\RouteTree\RouteTreeBuilder;

class Router implements RouterInterface
{
    private array $routes;
    private Route $currentRoute;
    private array $tree = [];
    public RouteTreeBuilder $routeTreeBuilder;

    private function add(string $method, string $path, string $controller): self
    {
        $this->currentRoute = new Route($method, $path, $controller);
        $this->updateCurrentRoute();
        $this->routeTreeBuilder->build($this->tree, $this->currentRoute, $method, $path);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function name(string $name): self
    {
        $this->currentRoute->setName($name);
        $this->updateCurrentRoute();

        return $this;
    }

    private function updateCurrentRoute(): void
    {
        $this->routes[$this->currentRoute->getKey()] = $this->currentRoute;
    }

    /**
     * {@inheritDoc}
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function getTree(): array
    {
        return $this->tree;
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $path, string $controller): RouterInterface
    {
        return $this->add(HttpRequest::METHOD_GET, $path, $controller);
    }

    /**
     * {@inheritDoc}
     */
    public function post(string $path, string $controller): RouterInterface
    {
        return $this->add(HttpRequest::METHOD_POST, $path, $controller);
    }
}
