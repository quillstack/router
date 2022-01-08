<?php

declare(strict_types=1);

namespace Quillstack\Router;

interface RouteInterface
{
    /**
     * @param string $name
     * @codeCoverageIgnore
     *
     * @return RouteInterface
     */
    public function setName(string $name): RouteInterface;

    /**
     * @codeCoverageIgnore
     *
     * @return string
     */
    public function getName(): string;

    /**
     * @codeCoverageIgnore
     *
     * @return string
     */
    public function getController(): string;

    /**
     * @codeCoverageIgnore
     *
     * @return bool
     */
    public function isSuccess(): bool;

    /**
     * @codeCoverageIgnore
     *
     * @return string
     */
    public function getKey(): string;
}
