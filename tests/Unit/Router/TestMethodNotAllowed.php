<?php

declare(strict_types=1);

namespace Quillstack\Router\Tests\Unit\Router;

use Quillstack\Router\Routes\MethodNotAllowedRoute;
use Quillstack\Router\Routes\NotFoundRoute;
use Quillstack\Router\Tests\Mocks\AbstractTest;
use Quillstack\Router\Tests\Mocks\Request\MockLoginRequest;
use Quillstack\Router\Tests\Mocks\Router\MockLoginController;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\Types\AssertBoolean;
use Quillstack\UnitTests\Types\AssertObject;

class TestMethodNotAllowed extends AbstractTest
{
    public const REQUEST = MockLoginRequest::class;
    public const SERVER = [
        'REQUEST_METHOD' => 'POST',
        'HTTP_HOST' => 'localhost:8000',
        'REQUEST_URI' => '/login',
        'SERVER_PROTOCOL' => '1.1',
    ];

    public function __construct(
        private AssertObject $assertObject,
        private AssertEqual $assertEqual,
        private AssertBoolean $assertBoolean
    ) {
        parent::__construct();
    }

    /**
     * The path is registered, just not for this method. It used to answer 404, which says
     * the resource does not exist.
     */
    public function aKnownPathAskedTheWrongWay()
    {
        $router = $this->getRouter();
        $router->get('/login', MockLoginController::class)->name('login');
        $route = $this->getRoute($router);

        $this->assertObject->instanceOf(MethodNotAllowedRoute::class, $route);
        $this->assertBoolean->isFalse($route->isSuccess());
        $this->assertEqual->equal(['GET', 'HEAD'], $route->getAllowedMethods());
    }

    /**
     * A 405 has to say what is allowed, and every method the path answers to belongs there —
     * HEAD included, because a path answering GET answers HEAD whether it was registered or
     * not.
     */
    public function everyMethodThePathAnswersToIsNamed()
    {
        $router = $this->getRouter();
        $router->get('/login', MockLoginController::class)->name('login');
        $router->put('/login', MockLoginController::class)->name('login.put');
        $router->delete('/login', MockLoginController::class)->name('login.delete');
        $route = $this->getRoute($router);

        $this->assertEqual->equal(['GET', 'HEAD', 'PUT', 'DELETE'], $route->getAllowedMethods());
    }

    /**
     * Nothing registered for the path at all is still nothing found.
     */
    public function anUnknownPathIsStillNotFound()
    {
        $router = $this->getRouter();
        $router->get('/register', MockLoginController::class)->name('register');
        $route = $this->getRoute($router);

        $this->assertObject->instanceOf(NotFoundRoute::class, $route);
    }
}
