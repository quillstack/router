<?php

declare(strict_types=1);

namespace Quillstack\Router;

class Route implements RouteInterface
{
    /**
     * Matches a path segment holding a parameter, written either as `:name` or as `{name}`.
     */
    private const PARAMETER_PATTERN = '#^(?::(?<colon>[A-Za-z_][A-Za-z0-9_]*)|\{(?<brace>[A-Za-z_][A-Za-z0-9_]*)\})$#';

    private string $name = '';

    /**
     * Names of the parameters found in the path, in the order they appear.
     */
    /**
     * @var string[]
     */
    private array $parameterNames;

    /**
     * Values matched for the parameters of this route, filled in while dispatching.
     */
    /**
     * @var array<string, string>
     */
    private array $parameters = [];

    public function __construct(private string $method, private string $path, private string $controller)
    {
        $this->parameterNames = self::readParameterNames($path);
    }

    /**
     * Reads the parameter names of the given path, keeping the order they appear in.
     *
     * @return string[]
     */
    public static function readParameterNames(string $path): array
    {
        $names = [];

        foreach (explode('/', $path) as $segment) {
            if (preg_match(self::PARAMETER_PATTERN, $segment, $matches)) {
                // Only the branch which matched is present in $matches, so neither key is
                // certain. The analysis reads both as always set; PHP disagrees out loud
                // with "Undefined array key" as soon as a `:name` is parsed.
                /** @phpstan-ignore nullCoalesce.offset */
                $names[] = ($matches['brace'] ?? '') !== '' ? $matches['brace'] : ($matches['colon'] ?? '');
            }
        }

        return $names;
    }

    /**
     * Tells whether the given path segment holds a parameter.
     */
    public static function isParameter(string $segment): bool
    {
        return (bool) preg_match(self::PARAMETER_PATTERN, $segment);
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
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * {@inheritDoc}
     */
    public function getPath(): string
    {
        return $this->path;
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

    /**
     * {@inheritDoc}
     */
    public function getParameterNames(): array
    {
        return $this->parameterNames;
    }

    /**
     * {@inheritDoc}
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * {@inheritDoc}
     */
    public function getParameter(string $name, ?string $default = null): ?string
    {
        return $this->parameters[$name] ?? $default;
    }

    /**
     * {@inheritDoc}
     */
    public function withParameters(array $parameters): RouteInterface
    {
        $new = clone $this;
        $new->parameters = $parameters;

        return $new;
    }
}
