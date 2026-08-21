<?php

declare(strict_types=1);

namespace Quillstack\Router\Tests\Unit\Router;

use Quillstack\HttpRequest\HttpRequest;
use Quillstack\Router\Tests\Mocks\AbstractTest;
use Quillstack\Router\Tests\Mocks\Request\MockLoginRequest;
use Quillstack\Router\Tests\Mocks\Router\MockUserController;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\Types\AssertBoolean;
use Quillstack\UnitTests\Types\AssertNull;

class TestHttpMethods extends AbstractTest
{
    public const REQUEST = MockLoginRequest::class;
    public const SERVER = [
        'REQUEST_METHOD' => 'DELETE',
        'HTTP_HOST' => 'localhost:8000',
        'REQUEST_URI' => '/users/13',
        'SERVER_PROTOCOL' => '1.1',
    ];

    public function __construct(
        private AssertEqual $assertEqual,
        private AssertBoolean $assertBoolean,
        private AssertNull $assertNull
    ) {
        parent::__construct();
    }

    public function noRoutesAreRegisteredByDefault()
    {
        $this->assertEqual->equal([], $this->getRouter()->getRoutes());
    }

    public function everyMethodRegistersItsOwnRoute()
    {
        $router = $this->getRouter();
        $router->get('/users/:user', MockUserController::class);
        $router->post('/users/:user', MockUserController::class);
        $router->put('/users/:user', MockUserController::class);
        $router->patch('/users/:user', MockUserController::class);
        $router->delete('/users/:user', MockUserController::class);
        $router->options('/users/:user', MockUserController::class);
        $router->head('/users/:user', MockUserController::class);

        $routes = $router->getRoutes();

        $this->assertEqual->equal(count(HttpRequest::AVAILABLE_METHODS), count($routes));

        foreach (HttpRequest::AVAILABLE_METHODS as $method) {
            $this->assertBoolean->isTrue(isset($routes["{$method} /users/:user"]));
        }
    }

    public function anyRegistersEveryMethodUnderOneName()
    {
        $router = $this->getRouter();
        $router->any('/users/:user', MockUserController::class)->name('user.any');
        $routes = $router->getRoutes();

        $this->assertEqual->equal(count(HttpRequest::AVAILABLE_METHODS), count($routes));

        foreach ($routes as $route) {
            $this->assertEqual->equal('user.any', $route->getName());
        }
    }

    public function matchRegistersOnlyTheGivenMethods()
    {
        $router = $this->getRouter();
        $router->match(['PUT', 'PATCH'], '/users/:user', MockUserController::class)->name('user.update');
        $routes = $router->getRoutes();

        $this->assertEqual->equal(2, count($routes));
        $this->assertEqual->equal('user.update', $routes['PUT /users/:user']->getName());
        $this->assertEqual->equal('user.update', $routes['PATCH /users/:user']->getName());
    }

    public function routesAreFoundByName()
    {
        $router = $this->getRouter();
        $router->get('/users/:user', MockUserController::class)->name('user.show');

        $this->assertEqual->equal('GET /users/:user', $router->getRoute('user.show')->getKey());
        $this->assertNull->isNull($router->getRoute('nothing.here'));
    }

    public function trailingSlashesAreNormalised()
    {
        $router = $this->getRouter();
        $router->get('/users/:user/', MockUserController::class);

        $this->assertBoolean->isTrue(isset($router->getRoutes()['GET /users/:user']));
    }

    public function deleteRequestIsDispatched()
    {
        $router = $this->getRouter();
        $router->get('/users/:user', MockUserController::class)->name('user.show');
        $router->delete('/users/:user', MockUserController::class)->name('user.delete');
        $route = $this->getRoute($router);

        $this->assertEqual->equal('user.delete', $route->getName());
        $this->assertEqual->equal('13', $route->getParameter('user'));
    }
}
