<?php

declare(strict_types=1);

namespace Quillstack\Router\Tests\Unit\Router;

use Quillstack\Router\Tests\Mocks\AbstractTest;
use Quillstack\Router\Tests\Mocks\Request\MockLoginRequest;
use Quillstack\Router\Tests\Mocks\Router\MockUserController;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\Types\AssertBoolean;

/**
 * Routing runs on the path, so a query string must not change which route is matched.
 */
class TestQueryStringIsIgnored extends AbstractTest
{
    public const REQUEST = MockLoginRequest::class;
    public const SERVER = [
        'REQUEST_METHOD' => 'GET',
        'HTTP_HOST' => 'localhost:8000',
        'REQUEST_URI' => '/users/13?page=2&sort=name',
        'SERVER_PROTOCOL' => '1.1',
    ];

    public function __construct(
        private AssertEqual $assertEqual,
        private AssertBoolean $assertBoolean
    ) {
        parent::__construct();
    }

    public function parameterRouteIgnoresTheQueryString()
    {
        $router = $this->getRouter();
        $router->get('/users/:user', MockUserController::class)->name('user');
        $route = $this->getRoute($router);

        $this->assertBoolean->isTrue($route->isSuccess());
        $this->assertEqual->equal('user', $route->getName());
        $this->assertEqual->equal('13', $route->getParameter('user'));
    }
}
