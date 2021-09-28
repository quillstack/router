<?php

declare(strict_types=1);

namespace QuillStack\Router;

use Psr\Http\Message\ServerRequestInterface;

interface DispatcherInterface
{
    /**
     * @param ServerRequestInterface $serverRequest
     * @codeCoverageIgnore
     *
     * @return RouteInterface|null
     */
    public function dispatch(ServerRequestInterface $serverRequest): ?RouteInterface;
}
