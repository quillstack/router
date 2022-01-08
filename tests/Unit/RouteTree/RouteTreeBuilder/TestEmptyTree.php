<?php

declare(strict_types=1);

namespace Quillstack\Router\Tests\Unit\RouteTree\RouteTreeBuilder;

use Quillstack\Router\Tests\Mocks\AbstractTest;
use Quillstack\Router\Tests\Mocks\Request\MockLoginRequest;
use Quillstack\Router\Tests\Mocks\Router\MockLoginController;
use Quillstack\Router\RouteTree\RouteTreeBuilder;
use Quillstack\UnitTests\AssertEmpty;
use Quillstack\UnitTests\AssertEqual;

class TestEmptyTree extends AbstractTest
{
    public const REQUEST = MockLoginRequest::class;
    public const SERVER = [
        'REQUEST_METHOD' => 'GET',
        'HTTP_HOST' => 'localhost:8000',
        'REQUEST_URI' => '/login',
        'SERVER_PROTOCOL' => '1.1',
    ];

    public function __construct(private AssertEmpty $assertEmpty, private AssertEqual $assertEqual)
    {
        parent::__construct();
    }

    public function emptyTree()
    {
        $router = $this->getRouter();
        $router->get('/login', MockLoginController::class)->name('login');
        $route = $this->getRoute($router);

        $tree = [];
        $routeTreeBuilder = new RouteTreeBuilder();
        $routeTreeBuilder->build($tree, $route, 'GET', '/login');

        $this->assertEmpty->isEmpty($tree);
        $this->assertEqual->equal([], $tree);
    }
}
