<?php

declare(strict_types=1);

namespace Quillstack\Router\Tests\Unit\RouteTree;

use Quillstack\Router\Route;
use Quillstack\Router\RouteTree\RouteTreeBuilder;
use Quillstack\Router\RouteTree\RouteTreeFinder;
use Quillstack\Router\Tests\Mocks\Router\MockUserController;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\Types\AssertNull;

class TestRouteTreeFinder
{
    private RouteTreeFinder $finder;

    public function __construct(
        private AssertEqual $assertEqual,
        private AssertNull $assertNull
    ) {
        $this->finder = new RouteTreeFinder();
    }

    private function treeFor(string $path): array
    {
        $tree = [];
        $route = new Route('GET', $path, MockUserController::class);
        (new RouteTreeBuilder())->build($tree, $route, 'GET', $path);

        return $tree;
    }

    public function anEmptyTreeMatchesNothing()
    {
        $this->assertNull->isNull($this->finder->findRoute([], ['GET', 'users', '13', '']));
    }

    public function aBranchLongerThanTheRouteMatchesNothing()
    {
        // Walking past the route lands on the route object while segments are still left.
        $tree = $this->treeFor('/users/:user');

        $this->assertNull->isNull($this->finder->findRoute($tree, ['GET', 'users', '13', 'posts', '']));
    }

    public function aBranchShorterThanTheRouteMatchesNothing()
    {
        $tree = $this->treeFor('/users/:user/posts/:post');

        $this->assertNull->isNull($this->finder->findRoute($tree, ['GET', 'users', '13', '']));
    }

    public function segmentsLeftAfterTheRouteMatchNothing()
    {
        // An empty segment in the middle, as in `/users/13//extra`, closes the branch on the
        // route while there are still segments to walk.
        $tree = $this->treeFor('/users/:user');

        $this->assertNull->isNull($this->finder->findRoute($tree, ['GET', 'users', '13', '', 'extra']));
    }

    public function valuesAreLeftUnboundWhenTheyDoNotMatchTheNames()
    {
        // A hand built tree whose route declares one parameter, reached through two
        // wildcards: the values cannot be paired with the names, so none are bound.
        $route = new Route('GET', '/users/:user', MockUserController::class);
        $tree = ['GET' => ['users' => ['*' => ['*' => ['' => $route]]]]];

        $found = $this->finder->findRoute($tree, ['GET', 'users', '13', 'posts', '']);

        $this->assertEqual->equal([], $found->getParameters());
    }
}
