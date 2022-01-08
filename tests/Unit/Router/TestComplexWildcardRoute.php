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

class TestComplexWildcardRoute extends AbstractTest
{
    public const REQUEST = MockLoginRequest::class;
    public const SERVER = [
        'REQUEST_METHOD' => 'GET',
        'HTTP_HOST' => 'localhost:8000',
        'REQUEST_URI' => '/user/13/name/15/test/12/extra/11/dog/3/forest',
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
        $path = '/user/:id/:name/:test/:dir/:number/:more/:count/:animal/:age/:name';
        $router = $this->getRouter();
        $router->get($path, MockUserController::class)->name('user');
        $router->post($path, MockUserController::class)->name('user.post');
        $router->get('/register', MockRegisterController::class)->name('register');
        $router->get('/login', MockLoginController::class)->name('login');
        $route = $this->getRoute($router);

        $this->assertEqual->equal("GET {$path}", $route->getKey());
        $this->assertEqual->equal('user', $route->getName());
        $this->assertEqual->equal(MockUserController::class, $route->getController());
        $this->assertBoolean->isTrue($route->isSuccess());
    }
}
