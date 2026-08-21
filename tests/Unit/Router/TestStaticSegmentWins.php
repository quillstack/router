<?php

declare(strict_types=1);

namespace Quillstack\Router\Tests\Unit\Router;

use Quillstack\Router\Tests\Mocks\AbstractTest;
use Quillstack\Router\Tests\Mocks\Request\MockLoginRequest;
use Quillstack\Router\Tests\Mocks\Router\MockRegisterController;
use Quillstack\Router\Tests\Mocks\Router\MockUserController;
use Quillstack\UnitTests\AssertEqual;

/**
 * A literal segment is more specific than a parameter, so it has to win the match.
 */
class TestStaticSegmentWins extends AbstractTest
{
    public const REQUEST = MockLoginRequest::class;
    public const SERVER = [
        'REQUEST_METHOD' => 'GET',
        'HTTP_HOST' => 'localhost:8000',
        'REQUEST_URI' => '/users/me/posts',
        'SERVER_PROTOCOL' => '1.1',
    ];

    public function __construct(private AssertEqual $assertEqual)
    {
        parent::__construct();
    }

    public function literalSegmentWinsOverParameter()
    {
        $router = $this->getRouter();
        $router->get('/users/:user/posts', MockUserController::class)->name('user.posts');
        $router->get('/users/me/:section', MockRegisterController::class)->name('my.section');
        $route = $this->getRoute($router);

        $this->assertEqual->equal('my.section', $route->getName());
        $this->assertEqual->equal(['section' => 'posts'], $route->getParameters());
    }
}
