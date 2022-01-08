<?php

declare(strict_types=1);

namespace Quillstack\Router;

class Route implements RouteInterface
{
    private string $name;

    public function __construct(private string $method, private string $path, private string $controller)
    {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function setName(string $name): RouteInterface
    {
        $this->name = $name;

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
    public function getKey(): string
    {
        return "{$this->method} {$this->path}";
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
        return true;
    }
}
