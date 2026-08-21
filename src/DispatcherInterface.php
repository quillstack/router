<?php

declare(strict_types=1);

namespace Quillstack\Router;

use Psr\Http\Message\ServerRequestInterface;

interface DispatcherInterface
{
    /**
     * Finds the route matching the request. When nothing matches, a route pointing at the
     * not-found controller is returned, so the result is never `null`.
     */
    public function dispatch(ServerRequestInterface $serverRequest): RouteInterface;
}
