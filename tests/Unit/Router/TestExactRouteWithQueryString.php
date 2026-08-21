<?php

declare(strict_types=1);

namespace Quillstack\Router\Tests\Unit\Router;

use Quillstack\Router\Tests\Mocks\AbstractTest;
use Quillstack\Router\Tests\Mocks\Request\MockLoginRequest;
use Quillstack\Router\Tests\Mocks\Router\MockRegisterController;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\Types\AssertBoolean;

class TestExactRouteWithQueryString extends AbstractTest
{
    public const REQUEST = MockLoginRequest::class;
    public const SERVER = [
        'REQUEST_METHOD' => 'GET',
        'HTTP_HOST' => 'localhost:8000',
        'REQUEST_URI' => '/register?ref=newsletter',
        'SERVER_PROTOCOL' => '1.1',
    ];

    public function __construct(
        private AssertEqual $assertEqual,
        private AssertBoolean $assertBoolean
    ) {
        parent::__construct();
    }

    public function exactRouteIgnoresTheQueryString()
    {
        $router = $this->getRouter();
        $router->get('/register', MockRegisterController::class)->name('register');
        $route = $this->getRoute($router);

        $this->assertBoolean->isTrue($route->isSuccess());
        $this->assertEqual->equal(MockRegisterController::class, $route->getController());
        $this->assertEqual->equal('register', $route->getName());
    }
}
