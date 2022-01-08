<?php

declare(strict_types=1);

namespace QuillStack\Router;

use QuillStack\Mocks\AbstractTest;
use Quillstack\Router\Tests\Mocks\Request\MockLoginRequest;
use Quillstack\Router\Tests\Mocks\Router\MockLoginController;
use Quillstack\Router\Tests\Mocks\Router\MockRegisterController;
use Quillstack\Router\Tests\Mocks\Router\MockUserController;

final class SimpleRouteTest extends AbstractTest
{
    public const REQUEST = MockLoginRequest::class;
    public const SERVER = [
        'REQUEST_METHOD' => 'GET',
        'HTTP_HOST' => 'localhost:8000',
        'REQUEST_URI' => '/login',
        'SERVER_PROTOCOL' => '1.1',
    ];

    public function testSimpleRoute()
    {
        $router = $this->getRouter();
        $router->get('/login', MockLoginController::class)->name('login');
        $router->get('/user/:id/ala', MockUserController::class)->name('user');
        $router->get('/register', MockRegisterController::class)->name('register');
        $route = $this->getRoute($router);

        $this->assertEquals('login', $route->getName());
        $this->assertEquals('GET /login', $route->getKey());
        $this->assertEquals(MockLoginController::class, $route->getController());
        $this->assertTrue($route->isSuccess());
    }
}
