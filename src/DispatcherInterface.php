<?php

declare(strict_types=1);

namespace Quillstack\Router;

use Psr\Http\Message\ServerRequestInterface;

interface DispatcherInterface
{
    /**
     * @param ServerRequestInterface $serverRequest
     *
     * @return RouteInterface|null
     */
    public function dispatch(ServerRequestInterface $serverRequest): ?RouteInterface;
}
