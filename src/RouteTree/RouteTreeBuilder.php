<?php

declare(strict_types=1);

namespace Quillstack\Router\RouteTree;

use Quillstack\Router\Route;
use Quillstack\Router\RouteInterface;

class RouteTreeBuilder
{
    /**
     * The tree node standing for a path segment holding a parameter.
     */
    public const WILDCARD = '*';

    /**
     * Adds the route to the tree, but only when its path holds parameters. Paths without
     * parameters are matched by an exact lookup, which needs no tree.
     *
     * @param array<string, mixed> $tree
     */
    public function build(array &$tree, RouteInterface $route, string $method, string $path): void
    {
        if (!$this->hasParameters($path)) {
            return;
        }

        $parts = explode('/', trim($method . $path, '/'));

        $this->add($route, $parts, $tree);
    }

    /**
     * @param string[] $indexes
     * @param array<string, mixed> $tree
     */
    private function add(RouteInterface $route, array $indexes, array &$tree): void
    {
        if ($indexes === []) {
            // Only the end of this branch is written. Replacing the whole node used to
            // throw away everything below it, so registering `/users/:user` after
            // `/users/:user/posts/:post` left the longer one unreachable.
            $tree[''] = $route;

            return;
        }

        $index = array_shift($indexes);

        if (Route::isParameter($index)) {
            $index = self::WILDCARD;
        }

        if (!isset($tree[$index]) || !is_array($tree[$index])) {
            $tree[$index] = [];
        }

        $this->add($route, $indexes, $tree[$index]);
    }

    private function hasParameters(string $path): bool
    {
        return Route::readParameterNames($path) !== [];
    }
}
