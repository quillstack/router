<?php

declare(strict_types=1);

namespace Quillstack\Router;

interface RouteInterface
{
    public function setName(string $name): RouteInterface;

    public function getName(): string;

    /**
     * The HTTP method this route was registered for.
     */
    public function getMethod(): string;

    /**
     * The path this route was registered for, parameters included, e.g. `/users/:id`.
     */
    public function getPath(): string;

    public function getController(): string;

    public function isSuccess(): bool;

    /**
     * A unique key of the route, built from the method and the path.
     */
    public function getKey(): string;

    /**
     * Names of the parameters declared in the path, in the order they appear.
     *
     * @return string[]
     */
    public function getParameterNames(): array;

    /**
     * Parameter values matched while dispatching, keyed by the parameter name.
     *
     * @return array<string, string>
     */
    public function getParameters(): array;

    public function getParameter(string $name, ?string $default = null): ?string;

    /**
     * Returns a copy of the route carrying the given parameter values.
     *
     * @param array<string, string> $parameters
     */
    public function withParameters(array $parameters): RouteInterface;
}
