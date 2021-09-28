<?php

declare(strict_types=1);

namespace QuillStack\Router;

use QuillStack\Mocks\AbstractTest;
use QuillStack\Mocks\Request\MockLoginRequest;
use QuillStack\Mocks\Router\MockLoginController;
use QuillStack\Mocks\Router\MockRegisterController;
use QuillStack\Mocks\Router\MockUserController;

final class RouteWithPostRequestTest extends AbstractTest
{
    public const REQUEST = MockLoginRequest::class;
    public const SERVER = [
        'REQUEST_METHOD' => 'POST',
        'HTTP_HOST' => 'localhost:8000',
        'REQUEST_URI' => '/login',
        'SERVER_PROTOCOL' => '1.1',
    ];

    public function testPostRoute()
    {
        $router = $this->getRouter();
        $router->post('/login', MockLoginController::class)->name('login.post');
        $router->get('/login', MockLoginController::class)->name('login.get');
        $router->get('/user/:id/ala', MockUserController::class)->name('user');
        $router->get('/register', MockRegisterController::class)->name('register');
        $route = $this->getRoute($router);

        $this->assertEquals('login.post', $route->getName());
        $this->assertEquals('POST /login', $route->getKey());
        $this->assertEquals(MockLoginController::class, $route->getController());
        $this->assertTrue($route->isSuccess());
    }
}
