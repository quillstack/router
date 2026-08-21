<?php

declare(strict_types=1);

namespace Quillstack\Router\Tests\Unit\Router;

use Quillstack\Router\Tests\Mocks\AbstractTest;
use Quillstack\Router\Tests\Mocks\Request\MockLoginRequest;
use Quillstack\Router\Tests\Mocks\Router\MockRegisterController;
use Quillstack\Router\Tests\Mocks\Router\MockUserController;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\Types\AssertBoolean;

class TestRouteParameters extends AbstractTest
{
    public const REQUEST = MockLoginRequest::class;
    public const SERVER = [
        'REQUEST_METHOD' => 'GET',
        'HTTP_HOST' => 'localhost:8000',
        'REQUEST_URI' => '/users/13/posts/7',
        'SERVER_PROTOCOL' => '1.1',
    ];

    public function __construct(
        private AssertEqual $assertEqual,
        private AssertBoolean $assertBoolean
    ) {
        parent::__construct();
    }

    public function parametersAreMatchedByName()
    {
        $router = $this->getRouter();
        $router->get('/users/:user/posts/:post', MockUserController::class)->name('user.post');
        $route = $this->getRoute($router);

        $this->assertBoolean->isTrue($route->isSuccess());
        $this->assertEqual->equal(['user' => '13', 'post' => '7'], $route->getParameters());
        $this->assertEqual->equal('13', $route->getParameter('user'));
        $this->assertEqual->equal('7', $route->getParameter('post'));
    }

    public function braceSyntaxIsSupported()
    {
        $router = $this->getRouter();
        $router->get('/users/{user}/posts/{post}', MockUserController::class);
        $route = $this->getRoute($router);

        $this->assertEqual->equal(['user' => '13', 'post' => '7'], $route->getParameters());
    }

    public function missingParameterReturnsTheDefault()
    {
        $router = $this->getRouter();
        $router->get('/users/:user/posts/:post', MockUserController::class);
        $route = $this->getRoute($router);

        $this->assertEqual->equal('none', $route->getParameter('unknown', 'none'));
    }

    public function registeredRoutesKeepTheirParameterNames()
    {
        $router = $this->getRouter();
        $router->get('/users/:user/posts/:post', MockRegisterController::class);
        $routes = $router->getRoutes();

        $this->assertEqual->equal(
            ['user', 'post'],
            $routes['GET /users/:user/posts/:post']->getParameterNames()
        );
    }
}
