<?php

declare(strict_types=1);

namespace Quillstack\Router\Tests\Mocks;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriFactoryInterface;
use Quillstack\DI\Container;
use Quillstack\Router\Dispatcher;
use Quillstack\Router\RouteInterface;
use Quillstack\Router\Router;
use Quillstack\ServerRequest\Factory\ServerRequest\GivenServerRequestFromGlobalsFactory;
use Quillstack\ServerRequest\Factory\ServerRequest\ServerRequestFromGlobalsFactory;
use QuillStack\Stream\InputStream;
use Quillstack\Uri\Factory\UriFactory;

abstract class AbstractTest
{
    /**
     * @var array
     */
    public const SERVER = [];

    /**
     * @var string
     */
    public const REQUEST = '';

    private Container $container;

    public function __construct()
    {
        $config = [
            StreamInterface::class => InputStream::class,
            UriFactoryInterface::class => UriFactory::class,
            ServerRequestFromGlobalsFactory::class => [
                'server' => static::SERVER,
            ],
        ];

        $this->container = new Container($config);
    }

    protected function getRequest(): ServerRequestInterface
    {
        $factory = $this->container->get(GivenServerRequestFromGlobalsFactory::class);

        return $factory->createGivenServerRequest(static::REQUEST);
    }

    protected function getRouter(): Router
    {
        return $this->container->get(Router::class);
    }

    protected function getRoute(Router $router): ?RouteInterface
    {
        $dispatcher = $this->container->get(Dispatcher::class);

        return $dispatcher->dispatch(
            $this->getRequest()
        );
    }
}
