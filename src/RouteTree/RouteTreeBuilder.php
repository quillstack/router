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
     */
    public function build(array &$tree, RouteInterface $route, string $method, string $path): void
    {
        if (!$this->hasParameters($path)) {
            return;
        }

        $parts = explode('/', trim($method . $path, '/'));

        $this->add($route, $parts, $tree);
    }

    private function add(RouteInterface $route, array $indexes, ?array &$tree = null): void
    {
        if (count($indexes) === 0) {
            $tree = [
                '' => $route,
            ];

            return;
        }

        $index = array_shift($indexes);

        if (Route::isParameter($index)) {
            $index = self::WILDCARD;
        }

        $this->add($route, $indexes, $tree[$index]);
    }

    private function hasParameters(string $path): bool
    {
        return Route::readParameterNames($path) !== [];
    }
}
