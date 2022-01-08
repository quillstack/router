<?php

declare(strict_types=1);

namespace Quillstack\Router\RouteTree;

use Quillstack\Router\RouteInterface;

final class RouteTreeBuilder
{
    /**
     * @param array $tree
     * @param RouteInterface $route
     * @param string $method
     * @param string $path
     */
    public function build(array &$tree, RouteInterface $route, string $method, string $path): void
    {
        if (!$this->hasWildcard($path)) {
            return;
        }


        $treePath = $method . $path;
        $parts = explode('/', trim($treePath, '/'));
        $this->add($route, $parts, $tree);
    }

    /**
     * @param RouteInterface $route
     * @param array $indexes
     * @param array|null $tree
     * @param array $value
     */
    private function add(RouteInterface $route, array $indexes, array &$tree = null, array $value = []): void
    {
        if (count($indexes) === 0) {
            $tree = [
                '' => $route,
            ];
        } else {
            $index = array_shift($indexes);

            if ($this->hasWildcard($index)) {
                $index = '*';
            }

            $this->add($route, $indexes, $tree[$index], $value);
        }
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    private function hasWildcard(string $path): bool
    {
        return is_string(strstr($path, ':'));
    }
}
