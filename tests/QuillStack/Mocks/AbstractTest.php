<?php

declare(strict_types=1);

namespace QuillStack\Mocks;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriFactoryInterface;
use QuillStack\DI\Container;
use QuillStack\Http\Request\Factory\ServerRequest\GivenRequestFromGlobalsFactory;
use QuillStack\Http\Request\Factory\ServerRequest\RequestFromGlobalsFactory;
use QuillStack\Http\Stream\InputStream;
use QuillStack\Http\Uri\Factory\UriFactory;
use QuillStack\Router\Dispatcher;
use QuillStack\Router\Route;
use QuillStack\Router\RouteInterface;
use QuillStack\Router\Router;

abstract class AbstractTest extends TestCase
{
    /**
     * @var array
     */
    public const SERVER = [];

    /**
     * @var string
     */
    public const REQUEST = '';

    /**
     * @var Container
     */
    private Container $container;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $config = [
            StreamInterface::class => InputStream::class,
            UriFactoryInterface::class => UriFactory::class,
            RequestFromGlobalsFactory::class => [
                'server' => static::SERVER,
            ],
        ];

        $this->container = new Container($config);
    }

    /**
     * @return ServerRequestInterface
     */
    public function getRequest(): ServerRequestInterface
    {
        $factory = $this->container->get(GivenRequestFromGlobalsFactory::class);

        return $factory->createGivenServerRequest(static::REQUEST);
    }

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->container->get(Router::class);
    }

    /**
     * @param Router $router
     *
     * @return Route|null
     */
    public function getRoute(Router $router): ?RouteInterface
    {
        $dispatcher = $this->container->get(Dispatcher::class);

        return $dispatcher->dispatch(
            $this->getRequest()
        );
    }
}
