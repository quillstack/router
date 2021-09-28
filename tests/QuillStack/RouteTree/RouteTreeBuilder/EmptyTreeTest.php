<?php

declare(strict_types=1);

namespace QuillStack\RouteTree\RouteTreeBuilder;

use QuillStack\Mocks\AbstractTest;
use QuillStack\Mocks\Request\MockLoginRequest;
use QuillStack\Mocks\Router\MockLoginController;
use QuillStack\Router\RouteTree\RouteTreeBuilder;

final class EmptyTreeTest extends AbstractTest
{
    public const REQUEST = MockLoginRequest::class;
    public const SERVER = [
        'REQUEST_METHOD' => 'GET',
        'HTTP_HOST' => 'localhost:8000',
        'REQUEST_URI' => '/login',
        'SERVER_PROTOCOL' => '1.1',
    ];

    public function testEmptyTree()
    {
        $router = $this->getRouter();
        $router->get('/login', MockLoginController::class)->name('login');
        $route = $this->getRoute($router);

        $tree = [];
        $routeTreeBuilder = new RouteTreeBuilder();
        $routeTreeBuilder->build($tree, $route, 'GET', '/login');

        $this->assertEmpty($tree);
        $this->assertEquals([], $tree);
    }
}
