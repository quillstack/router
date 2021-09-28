<?php

declare(strict_types=1);

namespace QuillStack\Router;

use QuillStack\Mocks\AbstractTest;
use QuillStack\Mocks\Request\MockLoginRequest;
use QuillStack\Mocks\Router\MockLoginController;
use QuillStack\Mocks\Router\MockRegisterController;
use QuillStack\Mocks\Router\MockUserController;

final class SimpleWildcardTest extends AbstractTest
{
    public const REQUEST = MockLoginRequest::class;
    public const SERVER = [
        'REQUEST_METHOD' => 'GET',
        'HTTP_HOST' => 'localhost:8000',
        'REQUEST_URI' => '/user/13/name',
        'SERVER_PROTOCOL' => '1.1',
    ];

    public function testSimpleWildcardRoute()
    {
        $router = $this->getRouter();
        $router->get('/user/:id/:name', MockUserController::class)->name('user');
        $router->get('/register', MockRegisterController::class)->name('register');
        $router->get('/login', MockLoginController::class)->name('login');
        $route = $this->getRoute($router);

        $this->assertEquals('GET /user/:id/:name', $route->getKey());
        $this->assertEquals('user', $route->getName());
        $this->assertEquals(MockUserController::class, $route->getController());
        $this->assertTrue($route->isSuccess());
    }
}
