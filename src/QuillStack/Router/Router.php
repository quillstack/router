<?php

declare(strict_types=1);

namespace QuillStack\Router;

use QuillStack\Http\Request\Request;
use QuillStack\Router\RouteTree\RouteTreeBuilder;

final class Router implements RouterInterface
{
    /**
     * @var array
     */
    private array $routes;

    /**
     * @var Route
     */
    private Route $currentRoute;

    /**
     * @var array
     */
    private array $tree = [];

    /**
     * @var RouteTreeBuilder
     */
    public RouteTreeBuilder $routeTreeBuilder;

    /**
     * @param string $method
     * @param string $path
     * @param string $controller
     *
     * @return $this
     */
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

    /**
     * @return array
     */
    public function getTree(): array
    {
        return $this->tree;
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $path, string $controller): RouterInterface
    {
        return $this->add(Request::METHOD_GET, $path, $controller);
    }

    /**
     * {@inheritDoc}
     */
    public function post(string $path, string $controller): RouterInterface
    {
        return $this->add(Request::METHOD_POST, $path, $controller);
    }
}
