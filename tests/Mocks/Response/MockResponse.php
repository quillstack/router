<?php

declare(strict_types=1);

namespace Quillstack\Router\Tests\Mocks\Response;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class MockResponse implements ResponseInterface
{
    public function getProtocolVersion()
    {
        //
    }

    public function withProtocolVersion($version)
    {
        //
    }

    public function getHeaders()
    {
        //
    }

    public function hasHeader($name)
    {
        //
    }

    public function getHeader($name)
    {
        //
    }

    public function getHeaderLine($name)
    {
        //
    }

    public function withHeader($name, $value)
    {
        //
    }

    public function withAddedHeader($name, $value)
    {
        //
    }

    public function withoutHeader($name)
    {
        //
    }

    public function getBody()
    {
        //
    }

    public function withBody(StreamInterface $body)
    {
        //
    }

    public function getStatusCode()
    {
        //
    }

    public function withStatus($code, $reasonPhrase = '')
    {
        //
    }

    public function getReasonPhrase()
    {
        //
    }
}
