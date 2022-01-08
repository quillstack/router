<?php

declare(strict_types=1);

namespace Quillstack\Router\Routes;

use Quillstack\Router\RouteInterface;

class NotFoundRoute implements RouteInterface
{
    private string $name = 'not-found';

    public function __construct(private string $controller)
    {
        //
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
