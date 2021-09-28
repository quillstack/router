<?php

declare(strict_types=1);

namespace QuillStack\Router;

final class Route implements RouteInterface
{
    /**
     * @var string
     */
    private string $method;

    /**
     * @var string
     */
    private string $path;

    /**
     * @var string
     */
    private string $controller;

    /**
     * @var string
     */
    private string $name;

    /**
     * @param string $method
     * @param string $path
     * @param string $controller
     */
    public function __construct(string $method, string $path, string $controller)
    {
        $this->method = $method;
        $this->path = $path;
        $this->controller = $controller;
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
