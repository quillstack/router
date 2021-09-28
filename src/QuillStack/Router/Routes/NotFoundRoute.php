<?php

declare(strict_types=1);

namespace QuillStack\Router\Routes;

use QuillStack\Router\Controllers\NotFoundController;
use QuillStack\Router\RouteInterface;

final class NotFoundRoute implements RouteInterface
{
    /**
     * @var string
     */
    private string $name = 'not-found';

    /**
     * @var string
     */
    private string $controller;

    /**
     * @param string $controller
     */
    public function __construct(string $controller)
    {
        $this->controller = $controller;
    }

    /**
     * {@inheritDoc}
     */
    public function setName(string $name): RouteInterface
    {
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * {@inheritDoc}
     */
    public function isSuccess(): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function getKey(): string
    {
        return $this->name;
    }
}
