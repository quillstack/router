<?php

declare(strict_types=1);

namespace Quillstack\Mocks\Router;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Quillstack\DI\Container;
use Quillstack\Http\Request\Factory\ServerRequest\GivenRequestFromGlobalsFactory;
use Quillstack\Http\Request\Factory\ServerRequest\RequestFromGlobalsFactory;
use Quillstack\Http\Stream\InputStream;
use Quillstack\Http\Uri\Factory\UriFactory;
use Quillstack\Router\Dispatcher;
use Quillstack\Router\Route;
use Quillstack\Router\RouteInterface;
use Quillstack\Router\Router;

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
