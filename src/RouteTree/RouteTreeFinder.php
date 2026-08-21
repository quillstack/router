<?php

declare(strict_types=1);

namespace Quillstack\Router\RouteTree;

use Quillstack\Router\RouteInterface;

class RouteTreeFinder
{
    /**
     * Walks the route tree along the given branch and returns the matching route, carrying the
     * values matched by its parameters. A literal segment always wins over a parameter, and the
     * search falls back to the parameter node when the literal one leads nowhere.
     */
    public function findRoute(array $tree, array $branch): ?RouteInterface
    {
        return $this->walk($tree, $branch, 0, []);
    }

    private function walk(mixed $node, array $branch, int $index, array $values): ?RouteInterface
    {
        if ($index === count($branch)) {
            return $node instanceof RouteInterface
                ? $this->withValues($node, $values)
                : null;
        }

        if (!is_array($node)) {
            return null;
        }

        $segment = $branch[$index];

        if (isset($node[$segment])) {
            $route = $this->walk($node[$segment], $branch, $index + 1, $values);

            if ($route !== null) {
                return $route;
            }
        }

        // An empty segment closes the branch, it must never be matched by a parameter.
        if ($segment === '' || !isset($node[RouteTreeBuilder::WILDCARD])) {
            return null;
        }

        $values[] = $segment;

        return $this->walk($node[RouteTreeBuilder::WILDCARD], $branch, $index + 1, $values);
    }

    /**
     * Binds the matched values to the parameter names declared by the route.
     */
    private function withValues(RouteInterface $route, array $values): RouteInterface
    {
        $names = $route->getParameterNames();

        if (count($names) !== count($values)) {
            return $route;
        }

        return $route->withParameters(
            array_combine($names, $values)
        );
    }
}
