<?php

declare(strict_types=1);

return [
    \Quillstack\Router\Tests\Unit\Router\TestRouteNotFound::class,
    \Quillstack\Router\Tests\Unit\RouteTree\RouteTreeBuilder\TestEmptyTree::class,
    \Quillstack\Router\Tests\Unit\RouteTree\RouteTreeBuilder\TestSimpleRouteTree::class,
    \Quillstack\Router\Tests\Unit\Router\TestComplexWildcardRoute::class,
    \Quillstack\Router\Tests\Unit\Router\TestSimpleWildcard::class,
    \Quillstack\Router\Tests\Unit\Router\TestRouteWithPostRequest::class,
    \Quillstack\Router\Tests\Unit\Router\TestSimpleRoute::class,

    \Quillstack\Router\Tests\Unit\Router\TestRouteParameters::class,
    \Quillstack\Router\Tests\Unit\Router\TestQueryStringIsIgnored::class,
    \Quillstack\Router\Tests\Unit\Router\TestExactRouteWithQueryString::class,
    \Quillstack\Router\Tests\Unit\Router\TestStaticSegmentWins::class,
    \Quillstack\Router\Tests\Unit\Router\TestBacktracking::class,
    \Quillstack\Router\Tests\Unit\Router\TestHeadFollowsGet::class,
    \Quillstack\Router\Tests\Unit\Router\TestHttpMethods::class,
    \Quillstack\Router\Tests\Unit\Router\TestMethodNotAllowed::class,
    \Quillstack\Router\Tests\Unit\Router\TestMethodNotAllowedWithParameters::class,
    \Quillstack\Router\Tests\Unit\Router\TestRouteApi::class,
    \Quillstack\Router\Tests\Unit\RouteTree\TestRouteTreeFinder::class,
    \Quillstack\Router\Tests\Unit\RouteTree\TestOverlappingRoutes::class,
];
