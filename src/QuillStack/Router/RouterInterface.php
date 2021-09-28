<?php

declare(strict_types=1);

namespace QuillStack\Router;

interface RouterInterface
{
    /**
     * @param string $path
     * @param string $controller
     * @codeCoverageIgnore
     *
     * @return RouterInterface
     */
    public function get(string $path, string $controller): RouterInterface;

    /**
     * @param string $path
     * @param string $controller
     * @codeCoverageIgnore
     *
     * @return RouterInterface
     */
    public function post(string $path, string $controller): RouterInterface;

    /**
     * @param string $name
     * @codeCoverageIgnore
     *
     * @return RouterInterface
     */
    public function name(string $name): RouterInterface;

    /**
     * @codeCoverageIgnore
     *
     * @return array
     */
    public function getRoutes(): array;
}
