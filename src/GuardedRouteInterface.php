<?php

declare(strict_types=1);

namespace Quillstack\Router;

/**
 * A route which says what reaching it requires.
 *
 * Kept apart from `RouteInterface` so that adding it breaks nothing: whatever implements a
 * route without answering these is a route nobody guards, which is what every route was
 * before this existed.
 */
interface GuardedRouteInterface
{
    /**
     * Whether this route may only be reached by somebody the application recognises.
     */
    public function requiresAuthentication(): bool;

    /**
     * The roles one of which the caller has to hold. Empty means any recognised caller will
     * do, where authentication is required at all.
     *
     * @return string[]
     */
    public function getRequiredRoles(): array;
}
