<?php

declare(strict_types=1);

namespace Quillstack\Router\Tests\Unit\RouteTree\RouteTreeBuilder;

use Quillstack\Router\Tests\Mocks\AbstractTest;
use Quillstack\Router\Tests\Mocks\Request\MockLoginRequest;
use Quillstack\Router\Tests\Mocks\Router\MockUserController;
use Quillstack\Router\RouteTree\RouteTreeBuilder;
use Quillstack\UnitTests\AssertEmpty;
use Quillstack\UnitTests\AssertEqual;

class TestSimpleRouteTree extends AbstractTest
{
    public const REQUEST = MockLoginRequest::class;
    public const SERVER = [
        'REQUEST_METHOD' => 'GET',
        'HTTP_HOST' => 'localhost:8000',
        'REQUEST_URI' => '/user/13/name',
        'SERVER_PROTOCOL' => '1.1',
    ];

    public function __construct(private AssertEmpty $assertEmpty, private AssertEqual $assertEqual)
    {
        parent::__construct();
    }

    public function simpleWildcardTree()
    {
        $router = $this->getRouter();
        $router->get('/user/:id/:name', MockUserController::class)->name('user');
        $route = $this->getRoute($router);

        $tree = [];
        $routeTreeBuilder = new RouteTreeBuilder();
        $routeTreeBuilder->build($tree, $route, 'GET', '/user/:id/:name');

        $this->assertEmpty->isNotEmpty($tree);
        $this->assertEqual->equal([
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
