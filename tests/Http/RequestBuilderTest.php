<?php

namespace Coresender\Tests\Http;

use PHPUnit\Framework\TestCase;
use Coresender\Http\RequestBuilder;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

class RequestBuilderTest extends TestCase
{
    /** @var RequestBuilder */
    private $requestBuilder;

    public function setUp(): void
    {
        parent::setUp();

        $requestFactoryMock = $this->createMock(RequestFactoryInterface::class);
        $streamFactoryMock = $this->createMock(StreamFactoryInterface::class);

        $this->requestBuilder = new RequestBuilder($requestFactoryMock, $streamFactoryMock);
    }

}
