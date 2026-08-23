<?php

declare(strict_types=1);

namespace Quillstack\Router\Tests\Unit\Router;

use Quillstack\Router\Exceptions\RouteNameNotSetException;
use Quillstack\Router\GuardedRouteInterface;
use Quillstack\Router\Tests\Mocks\AbstractTest;
use Quillstack\Router\Tests\Mocks\Request\MockLoginRequest;
use Quillstack\Router\Tests\Mocks\Router\MockLoginController;
use Quillstack\Router\Tests\Mocks\Router\MockUserController;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\AssertExceptions;
use Quillstack\UnitTests\Types\AssertBoolean;

/**
 * A route says what reaching it requires.
 *
 * Declared here rather than remembered in each controller: a guarantee which has to be
 * repeated is a guarantee somebody will one day not repeat.
 */
class TestGuardedRoutes extends AbstractTest
{
    public const REQUEST = MockLoginRequest::class;
    public const SERVER = [
        'REQUEST_METHOD' => 'GET',
        'HTTP_HOST' => 'localhost:8000',
        'REQUEST_URI' => '/orders',
        'SERVER_PROTOCOL' => '1.1',
    ];

    public function __construct(
        private AssertEqual $assertEqual,
        private AssertBoolean $assertBoolean,
        private AssertExceptions $assertExceptions
    ) {
        parent::__construct();
    }

    /**
     * Nothing is guarded unless it says so, so adding this to the router changed no route
     * anybody had already written.
     */
    public function aRouteIsOpenUnlessItSaysOtherwise()
    {
        $router = $this->getRouter();
        $router->get('/orders', MockLoginController::class)->name('orders');

        $route = $this->getRoute($router);

        $this->assertBoolean->isTrue($route instanceof GuardedRouteInterface);
        $this->assertBoolean->isFalse($route->requiresAuthentication());
        $this->assertEqual->equal([], $route->getRequiredRoles());
    }

    public function aGuardedRouteSaysSo()
    {
        $router = $this->getRouter();
        $router->get('/orders', MockLoginController::class)->name('orders')->requireAuthentication();

        $route = $this->getRoute($router);

        $this->assertBoolean->isTrue($route->requiresAuthentication());
        $this->assertEqual->equal([], $route->getRequiredRoles());
    }

    /**
     * Roles are named where they are needed, and any one of them will do.
     */
    public function aRouteMayAskForARole()
    {
        $router = $this->getRouter();
        $router->get('/orders', MockLoginController::class)->requireAuthentication('admin', 'support');

        $route = $this->getRoute($router);

        $this->assertBoolean->isTrue($route->requiresAuthentication());
        $this->assertEqual->equal(['admin', 'support'], $route->getRequiredRoles());
    }

    /**
     * Naming it and guarding it read the same way round, in either order.
     */
    public function itReadsTheSameWayRoundEitherWay()
    {
        $router = $this->getRouter();
        $router->get('/orders', MockLoginController::class)->requireAuthentication('admin')->name('orders');

        $route = $this->getRoute($router);

        $this->assertEqual->equal('orders', $route->getName());
        $this->assertBoolean->isTrue($route->requiresAuthentication());
    }

    /**
     * Registered for several methods at once, guarded for all of them.
     */
    public function guardingSeveralAtOnce()
    {
        $router = $this->getRouter();
        $router->match(['GET', 'POST'], '/orders', MockLoginController::class)->requireAuthentication('admin');

        foreach ($router->getRoutes() as $route) {
            $this->assertBoolean->isTrue($route->requiresAuthentication());
            $this->assertEqual->equal(['admin'], $route->getRequiredRoles());
        }
    }

    /**
     * What the route requires survives the values matched into it.
     */
    public function whatItRequiresSurvivesDispatching()
    {
        $router = $this->getRouter();
        $router->get('/orders', MockUserController::class)->requireAuthentication('admin');

        $route = $this->getRoute($router)->withParameters(['id' => '42']);

        $this->assertBoolean->isTrue($route->requiresAuthentication());
        $this->assertEqual->equal(['admin'], $route->getRequiredRoles());
    }

    /**
     * Nothing matched is nothing to guard: whatever answers a 404 says so itself.
     */
    public function nothingMatchedIsNothingToGuard()
    {
        $router = $this->getRouter();
        $router->get('/somewhere-else', MockLoginController::class)->requireAuthentication();

        $route = $this->getRoute($router);

        $this->assertBoolean->isFalse($route->isSuccess());
        $this->assertBoolean->isFalse($route instanceof GuardedRouteInterface);
    }

    public function guardingBeforeThereIsARouteSaysSo()
    {
        $this->assertExceptions->expect(RouteNameNotSetException::class);

        $this->getRouter()->requireAuthentication('admin');
    }
}
