<?php

declare(strict_types=1);

namespace Quillstack\Router\Tests\Unit\Router;

use Quillstack\Router\Routes\MethodNotAllowedRoute;
use Quillstack\Router\Tests\Mocks\AbstractTest;
use Quillstack\Router\Tests\Mocks\Request\MockLoginRequest;
use Quillstack\Router\Tests\Mocks\Router\MockUserController;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\Types\AssertObject;

class TestMethodNotAllowedWithParameters extends AbstractTest
{
    public const REQUEST = MockLoginRequest::class;
    public const SERVER = [
        'REQUEST_METHOD' => 'DELETE',
        'HTTP_HOST' => 'localhost:8000',
        'REQUEST_URI' => '/users/42',
        'SERVER_PROTOCOL' => '1.1',
    ];

    public function __construct(
        private AssertObject $assertObject,
        private AssertEqual $assertEqual
    ) {
        parent::__construct();
    }

    /**
     * A path holding parameters is matched through the tree rather than by key, so it has
     * to be looked at the same way when working out what is allowed.
     */
    public function aPathWithParametersIsAllowedForItsOwnMethods()
    {
        $router = $this->getRouter();
        $router->get('/users/:id', MockUserController::class)->name('users.show');
        $router->patch('/users/:id', MockUserController::class)->name('users.update');
        $route = $this->getRoute($router);

        $this->assertObject->instanceOf(MethodNotAllowedRoute::class, $route);
        $this->assertEqual->equal(['GET', 'PATCH'], $route->getAllowedMethods());
    }
}
