<?php

declare(strict_types=1);

namespace Quillstack\Router\RouteTree;

use Quillstack\Router\RouteInterface;

final class RouteTreeFinder
{
    /**
     * @param array $routeFinder
     * @param array $branch
     *
     * @return RouteInterface|null
     */
    public function findRoute(array &$routeFinder, array $branch): ?RouteInterface
    {
        foreach ($branch as $key) {
            $found = &$routeFinder[$key];

            if (!$found) {
                $routeFinder = &$routeFinder['*'];
            } else {
                $routeFinder = $found;
            }
        }

        if ($routeFinder instanceof RouteInterface) {
            return $routeFinder;
        }

        return null;
    }
}
