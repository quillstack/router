<?php

declare(strict_types=1);

namespace Quillstack\Router\Tests\Mocks\Router;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Quillstack\Router\Tests\Mocks\Response\MockResponse;

class MockRegisterController implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new MockResponse();
    }
}
