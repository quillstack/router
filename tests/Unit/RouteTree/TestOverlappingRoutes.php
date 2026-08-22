<?php

declare(strict_types=1);

namespace Quillstack\Router\Tests\Unit\RouteTree;

use Quillstack\Router\Router;
use Quillstack\Router\RouteTree\RouteTreeBuilder;
use Quillstack\Router\Tests\Mocks\Router\MockRegisterController;
use Quillstack\Router\Tests\Mocks\Router\MockUserController;
use Quillstack\UnitTests\Types\AssertArray;

/**
 * Writing a route used to replace the whole node it ended on, throwing away everything
 * below it: registering `/users/:user` after `/users/:user/posts/:post` left the longer one
 * with nowhere to be found.
 */
class TestOverlappingRoutes
{
    public function __construct(private AssertArray $assertArray)
    {
        //
    }

    private function router(): Router
    {
        return new Router(new RouteTreeBuilder());
    }

    public function theShorterRouteDoesNotEraseTheLongerOne()
    {
        $router = $this->router();
        $router->get('/users/:user/posts/:post', MockUserController::class);
        $router->get('/users/:user', MockRegisterController::class);

        $node = $router->getTree()['GET']['users']['*'];

        $this->assertArray->hasKey('posts', $node);
        $this->assertArray->hasKey('', $node);
    }

    public function theOtherOrderWorksTheSameWay()
    {
        $router = $this->router();
        $router->get('/users/:user', MockRegisterController::class);
        $router->get('/users/:user/posts/:post', MockUserController::class);

        $node = $router->getTree()['GET']['users']['*'];

        $this->assertArray->hasKey('posts', $node);
        $this->assertArray->hasKey('', $node);
    }

    public function branchesSideBySideBothSurvive()
    {
        $router = $this->router();
        $router->get('/users/:user/posts', MockUserController::class);
        $router->get('/users/:user/comments', MockRegisterController::class);

        $node = $router->getTree()['GET']['users']['*'];

        $this->assertArray->hasKey('posts', $node);
        $this->assertArray->hasKey('comments', $node);
    }
}
