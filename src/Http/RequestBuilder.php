<?php

namespace Coresender\Http;

use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\RequestInterface;

class RequestBuilder
{
    /** @var RequestFactoryInterface */
    private $requestFactory;

    /** @var StreamFactoryInterface */
    private $streamFactory;

    public function __construct(?RequestFactoryInterface $requestFactory = null, ?StreamFactoryInterface $streamFactory = null)
    {
        $this->requestFactory = $requestFactory ?: Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = $streamFactory ?: Psr17FactoryDiscovery::findStreamFactory();
    }

    public function buildRequest(string $method, string $uri, array $data = null): RequestInterface
    {
        $request = $this->requestFactory->createRequest($method, $uri);

        if ($data) {
            $stream = $this->streamFactory->createStream(json_encode($data));

            $request = $request->withHeader('Content-Type', 'application/json');
            $request = $request->withBody($stream);
        }


        return $request;
    }
}
