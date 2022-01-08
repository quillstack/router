<?php

declare(strict_types=1);

namespace Quillstack\Router;

interface RouterInterface
{
    /**
     * @param string $path
     * @param string $controller
     *
     * @return RouterInterface
     */
    public function get(string $path, string $controller): RouterInterface;

    /**
     * @param string $path
     * @param string $controller
     *
     * @return RouterInterface
     */
    public function post(string $path, string $controller): RouterInterface;

    /**
     * @param string $name
     *
     * @return RouterInterface
     */
    public function name(string $name): RouterInterface;

    /**
     * @return array
     */
    public function getRoutes(): array;
}
