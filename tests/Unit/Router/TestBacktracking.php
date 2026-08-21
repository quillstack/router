<?php

declare(strict_types=1);

namespace Quillstack\Router\Tests\Unit\Router;

use Quillstack\Router\Tests\Mocks\AbstractTest;
use Quillstack\Router\Tests\Mocks\Request\MockLoginRequest;
use Quillstack\Router\Tests\Mocks\Router\MockRegisterController;
use Quillstack\Router\Tests\Mocks\Router\MockUserController;
use Quillstack\UnitTests\AssertEqual;

/**
 * The literal branch is tried first, and when it leads nowhere the search has to fall back
 * to the parameter branch instead of giving up.
 */
class TestBacktracking extends AbstractTest
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

    public function fallsBackToTheParameterBranch()
    {
        $router = $this->getRouter();
        $router->get('/users/me/settings/:tab', MockRegisterController::class)->name('my.settings');
        $router->get('/users/:user/posts', MockUserController::class)->name('user.posts');
        $route = $this->getRoute($router);

        $this->assertEqual->equal('user.posts', $route->getName());
        $this->assertEqual->equal(['user' => 'me'], $route->getParameters());
    }
}
