<?php

declare(strict_types=1);

namespace Quillstack\Router\Tests\Unit;

use Quillstack\Router\Dispatcher;
use Quillstack\Router\Router;
use Quillstack\HeaderBag\HeaderBag;
use Quillstack\ServerRequest\ServerRequest;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\Uri\Factory\UriFactory;

/**
 * A router which cannot be built without first building something else is a container's job to
 * assemble, and registering a few routes should not require one.
 */
class TestBuiltByHand
{
    public function __construct(private AssertEqual $assertEqual)
    {
        //
    }

    public function aRouterNeedsNothingToBeBuilt()
    {
        $router = new Router();
        $router->get('/users/:id', 'UserController');

        $this->assertEqual->equal(1, count($router->getRoutes()));
    }

    public function andNeitherDoesADispatcher()
    {
        $router = new Router();
        $router->get('/users/:id', 'UserController');

        $request = new ServerRequest(
            'GET',
            (new UriFactory())->createUri('http://localhost/users/42'),
            '1.1',
            new HeaderBag()
        );

        $route = (new Dispatcher($router))->dispatch($request);

        $this->assertEqual->equal(true, $route->isSuccess());
        $this->assertEqual->equal('42', $route->getParameter('id'));
    }
}
