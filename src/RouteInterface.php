<?php

declare(strict_types=1);

namespace Quillstack\Router;

interface RouteInterface
{
    /**
     * @param string $name
     *
     * @return RouteInterface
     */
    public function setName(string $name): RouteInterface;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getController(): string;

    /**
     * @return bool
     */
    public function isSuccess(): bool;

    /**
     * @return string
     */
    public function getKey(): string;
}
