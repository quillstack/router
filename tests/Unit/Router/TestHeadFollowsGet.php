<?php

declare(strict_types=1);

namespace Quillstack\Router\Tests\Unit\Router;

use Quillstack\Router\Tests\Mocks\AbstractTest;
use Quillstack\Router\Tests\Mocks\Request\MockLoginRequest;
use Quillstack\Router\Tests\Mocks\Router\MockLoginController;
use Quillstack\Router\Tests\Mocks\Router\MockUserController;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\Types\AssertBoolean;

class TestHeadFollowsGet extends AbstractTest
{
    public const REQUEST = MockLoginRequest::class;
    public const SERVER = [
        'REQUEST_METHOD' => 'HEAD',
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

    /**
     * A server answering GET answers HEAD. Asking for one used to be answered with 405,
     * which meant every well-behaved client checking a resource before fetching it was
     * turned away.
     */
    public function headIsAnsweredByTheGetRoute()
    {
        $router = $this->getRouter();
        $router->get('/login', MockLoginController::class)->name('login');
        $route = $this->getRoute($router);

        $this->assertBoolean->isTrue($route->isSuccess());
        $this->assertEqual->equal(MockLoginController::class, $route->getController());
        $this->assertEqual->equal('login', $route->getName());
    }

    /**
     * A route registered for HEAD is the one that answers, rather than the GET one.
     */
    public function aRouteRegisteredForHeadWins()
    {
        $router = $this->getRouter();
        $router->get('/login', MockLoginController::class)->name('login');
        $router->head('/login', MockUserController::class)->name('login.head');
        $route = $this->getRoute($router);

        $this->assertEqual->equal(MockUserController::class, $route->getController());
        $this->assertEqual->equal('login.head', $route->getName());
    }
}
