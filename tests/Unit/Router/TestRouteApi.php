<?php

declare(strict_types=1);

namespace Quillstack\Router\Tests\Unit\Router;

use Quillstack\Router\Exceptions\RouteNameNotSetException;
use Quillstack\Router\Route;
use Quillstack\Router\RouteTree\RouteTreeBuilder;
use Quillstack\Router\Router;
use Quillstack\Router\Routes\NotFoundRoute;
use Quillstack\Router\Tests\Mocks\Router\MockUserController;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\AssertExceptions;
use Quillstack\UnitTests\Types\AssertBoolean;
use Quillstack\UnitTests\Types\AssertNull;

class TestRouteApi
{
    public function __construct(
        private AssertEqual $assertEqual,
        private AssertBoolean $assertBoolean,
        private AssertNull $assertNull,
        private AssertExceptions $assertExceptions
    ) {
        //
    }

    public function aRouteKnowsItsMethodAndPath()
    {
        $route = new Route('GET', '/users/:user', MockUserController::class);

        $this->assertEqual->equal('GET', $route->getMethod());
        $this->assertEqual->equal('/users/:user', $route->getPath());
        $this->assertEqual->equal(['user'], $route->getParameterNames());
        $this->assertEqual->equal([], $route->getParameters());
        $this->assertEqual->equal('', $route->getName());
    }

    public function withParametersLeavesTheOriginalUntouched()
    {
        $route = new Route('GET', '/users/:user', MockUserController::class);
        $bound = $route->withParameters(['user' => '13']);

        $this->assertEqual->equal([], $route->getParameters());
        $this->assertEqual->equal(['user' => '13'], $bound->getParameters());
        $this->assertEqual->equal('13', $bound->getParameter('user'));
        $this->assertNull->isNull($bound->getParameter('missing'));
    }

    public function namingWithoutARouteIsReported()
    {
        $router = new Router(new RouteTreeBuilder());

        $this->assertExceptions->expect(RouteNameNotSetException::class);

        $router->name('nothing');
    }

    public function theNotFoundRouteAnswersTheWholeContract()
    {
        $route = new NotFoundRoute(MockUserController::class);

        $this->assertBoolean->isFalse($route->isSuccess());
        $this->assertEqual->equal(MockUserController::class, $route->getController());
        $this->assertEqual->equal('not-found', $route->getName());
        $this->assertEqual->equal('not-found', $route->getKey());
        $this->assertEqual->equal('', $route->getMethod());
        $this->assertEqual->equal('', $route->getPath());
        $this->assertEqual->equal([], $route->getParameterNames());
        $this->assertEqual->equal([], $route->getParameters());
        $this->assertEqual->equal('none', $route->getParameter('user', 'none'));

        // It carries no parameters and cannot be renamed, so both return the route itself.
        $this->assertEqual->equal($route, $route->withParameters(['user' => '13']));
        $this->assertEqual->equal($route, $route->setName('other'));
    }
}
