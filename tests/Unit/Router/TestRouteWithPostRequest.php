<?php

declare(strict_types=1);

namespace Quillstack\Router\Tests\Unit\Router;

use Quillstack\Router\Tests\Mocks\AbstractTest;
use Quillstack\Router\Tests\Mocks\Request\MockLoginRequest;
use Quillstack\Router\Tests\Mocks\Router\MockLoginController;
use Quillstack\Router\Tests\Mocks\Router\MockRegisterController;
use Quillstack\Router\Tests\Mocks\Router\MockUserController;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\Types\AssertBoolean;

class TestRouteWithPostRequest extends AbstractTest
{
    public const REQUEST = MockLoginRequest::class;
    public const SERVER = [
        'REQUEST_METHOD' => 'POST',
        'HTTP_HOST' => 'localhost:8000',
        'REQUEST_URI' => '/login',
        'SERVER_PROTOCOL' => '1.1',
    ];

    public function __construct(
        private AssertEqual $assertEqual,
        private AssertBoolean $assertBoolean
    ) {
        parent::__construct();
    }

    public function postRoute()
    {
        $router = $this->getRouter();
        $router->post('/login', MockLoginController::class)->name('login.post');
        $router->get('/login', MockLoginController::class)->name('login.get');
        $router->get('/user/:id/ala', MockUserController::class)->name('user');
        $router->get('/register', MockRegisterController::class)->name('register');
        $route = $this->getRoute($router);

        $this->assertEqual->equal('login.post', $route->getName());
        $this->assertEqual->equal('POST /login', $route->getKey());
        $this->assertEqual->equal(MockLoginController::class, $route->getController());
        $this->assertBoolean->isTrue($route->isSuccess());
    }
}
