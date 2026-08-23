<?php

declare(strict_types=1);

namespace Quillstack\Router;

interface RouterInterface
{
    /**
     * Registers a route for a single HTTP method.
     */
    public function map(string $method, string $path, string $controller): RouterInterface;

    /**
     * Registers the same path and controller for every given HTTP method.
     *
     * @param string[] $methods
     */
    public function match(array $methods, string $path, string $controller): RouterInterface;

    /**
     * Registers the path for every HTTP method the framework supports.
     */
    public function any(string $path, string $controller): RouterInterface;

    public function get(string $path, string $controller): RouterInterface;

    public function post(string $path, string $controller): RouterInterface;

    public function put(string $path, string $controller): RouterInterface;

    public function patch(string $path, string $controller): RouterInterface;

    public function delete(string $path, string $controller): RouterInterface;

    public function options(string $path, string $controller): RouterInterface;

    public function head(string $path, string $controller): RouterInterface;

    /**
     * Names the routes registered by the previous call.
     */
    public function name(string $name): RouterInterface;

    /**
     * Says that the routes just registered may only be reached by somebody the application
     * recognises, and — where roles are given — that they must hold one of them.
     */
    public function requireAuthentication(string ...$roles): RouterInterface;

    /**
     * @return RouteInterface[]
     */
    public function getRoutes(): array;

    /**
     * Finds a registered route by its name.
     */
    public function getRoute(string $name): ?RouteInterface;
}
