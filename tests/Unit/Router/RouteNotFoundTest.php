<?php

declare(strict_types=1);

namespace QuillStack\Router;

use QuillStack\Mocks\AbstractTest;
use QuillStack\Mocks\Request\MockLoginRequest;
use QuillStack\Mocks\Router\MockLoginController;
use QuillStack\Mocks\Router\MockRegisterController;
use QuillStack\Mocks\Router\MockUserController;
use QuillStack\Router\Routes\NotFoundRoute;

final class RouteNotFoundTest extends AbstractTest
{
    public const REQUEST = MockLoginRequest::class;
    public const SERVER = [
        'REQUEST_METHOD' => 'GET',
        'HTTP_HOST' => 'localhost:8000',
        'REQUEST_URI' => '/404',
        'SERVER_PROTOCOL' => '1.1',
    ];

    public function testNotFoundRoute()
    {
        $router = $this->getRouter();
        $router->get('/login', MockLoginController::class)->name('login');
        $router->get('/user/:id/ala', MockUserController::class)->name('user');
        $router->get('/register', MockRegisterController::class)->name('register');
        $route = $this->getRoute($router);

        $this->assertInstanceOf(NotFoundRoute::class, $route);
        $this->assertEquals('', $route->getController());
        $this->assertFalse($route->isSuccess());
    }
}
