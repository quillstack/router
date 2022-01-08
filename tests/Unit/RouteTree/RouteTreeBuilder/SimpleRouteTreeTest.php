<?php

declare(strict_types=1);

namespace QuillStack\RouteTree\RouteTreeBuilder;

use QuillStack\Mocks\AbstractTest;
use Quillstack\Router\Tests\Mocks\Request\MockLoginRequest;
use Quillstack\Router\Tests\Mocks\Router\MockUserController;
use Quillstack\Router\RouteTree\RouteTreeBuilder;

final class SimpleRouteTreeTest extends AbstractTest
{
    public const REQUEST = MockLoginRequest::class;
    public const SERVER = [
        'REQUEST_METHOD' => 'GET',
        'HTTP_HOST' => 'localhost:8000',
        'REQUEST_URI' => '/user/13/name',
        'SERVER_PROTOCOL' => '1.1',
    ];

    public function testSimpleWildcardTree()
    {
        $router = $this->getRouter();
        $router->get('/user/:id/:name', MockUserController::class)->name('user');
        $route = $this->getRoute($router);

        $tree = [];
        $routeTreeBuilder = new RouteTreeBuilder();
        $routeTreeBuilder->build($tree, $route, 'GET', '/user/:id/:name');

        $this->assertNotEmpty($tree);
        $this->assertEquals([
            'GET' => [
                'user' => [
                    '*' => [
                        '*' => [
                            '' => $route,
                        ],
                    ],
                ],
            ],
        ], $tree);
    }
}
