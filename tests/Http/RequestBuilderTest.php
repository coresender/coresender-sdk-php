<?php

namespace Coresender\Tests\Http;

use PHPUnit\Framework\TestCase;
use Coresender\Http\RequestBuilder;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\RequestInterface;

class RequestBuilderTest extends TestCase
{
    /** @test */
    public function it_should_create_get_request(): void
    {
        $requestFactoryMock = $this->createMock(RequestFactoryInterface::class);
        $streamFactoryMock = $this->createMock(StreamFactoryInterface::class);

        $requestBuilder = new RequestBuilder($requestFactoryMock, $streamFactoryMock);

        $request = $this->createMock(RequestInterface::class);

        $requestFactoryMock
            ->expects($this->once())
            ->method('createRequest')
            ->with('GET', 'http://www.example.com')
            ->willReturn($request)
        ;

        $result = $requestBuilder->buildRequest('GET', 'http://www.example.com');

        $this->assertSame($request, $result);
    }

    /** @test */
    public function it_should_create_post_request_with_json_payload(): void
    {
        $requestFactoryMock = $this->createMock(RequestFactoryInterface::class);
        $streamFactoryMock = $this->createMock(StreamFactoryInterface::class);

        $requestBuilder = new RequestBuilder($requestFactoryMock, $streamFactoryMock);

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('withHeader')
            ->willReturn($request)
        ;

        $request
            ->expects($this->once())
            ->method('withBody')
            ->willReturn($request)
        ;

        $data = ['body' => 'content'];

        $encodedContent = json_encode($data);

        $streamFactoryMock
            ->expects($this->once())
            ->method('createStream')
            ->with($encodedContent)
        ;

        $requestFactoryMock
            ->expects($this->once())
            ->method('createRequest')
            ->with('POST', 'http://www.example.com')
            ->willReturn($request)
        ;

        $result = $requestBuilder->buildRequest('POST', 'http://www.example.com', $data);

        $this->assertSame($request, $result);
    }
}
