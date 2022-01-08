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

class TestSimpleWildcard extends AbstractTest
{
    public const REQUEST = MockLoginRequest::class;
    public const SERVER = [
        'REQUEST_METHOD' => 'GET',
        'HTTP_HOST' => 'localhost:8000',
        'REQUEST_URI' => '/user/13/name',
        'SERVER_PROTOCOL' => '1.1',
    ];

    public function __construct(
        private AssertEqual $assertEqual,
        private AssertBoolean $assertBoolean
    ) {
        parent::__construct();
    }

    public function simpleWildcardRoute()
    {
        $router = $this->getRouter();
        $router->get('/user/:id/:name', MockUserController::class)->name('user');
        $router->get('/register', MockRegisterController::class)->name('register');
        $router->get('/login', MockLoginController::class)->name('login');
        $route = $this->getRoute($router);

        $this->assertEqual->equal('GET /user/:id/:name', $route->getKey());
        $this->assertEqual->equal('user', $route->getName());
        $this->assertEqual->equal(MockUserController::class, $route->getController());
        $this->assertBoolean->isTrue($route->isSuccess());
    }
}
