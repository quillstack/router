<?php

declare(strict_types=1);

namespace Quillstack\Router\Routes;

use Quillstack\Router\RouteInterface;

/**
 * The path is known, the method is not. Answering this with 404 says the resource does not
 * exist, which is a different thing and sends a client looking in the wrong place.
 */
class MethodNotAllowedRoute implements RouteInterface
{
    private string $name = 'method-not-allowed';

    /**
     * @param string[] $allowedMethods the methods this path is registered for, which a 405
     *                                 has to name
     */
    public function __construct(private readonly array $allowedMethods = [])
    {
        //
    }

    /**
     * @return string[]
     */
    public function getAllowedMethods(): array
    {
        return $this->allowedMethods;
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
    public function getMethod(): string
    {
        return '';
    }

    /**
     * {@inheritDoc}
     */
    public function getPath(): string
    {
        return '';
    }

    /**
     * {@inheritDoc}
     */
    public function getController(): string
    {
        return '';
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

    /**
     * {@inheritDoc}
     */
    public function getParameterNames(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function getParameters(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function getParameter(string $name, ?string $default = null): ?string
    {
        return $default;
    }

    /**
     * {@inheritDoc}
     */
    public function withParameters(array $parameters): RouteInterface
    {
        return $this;
    }
}
