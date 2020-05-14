<?php

declare(strict_types=1);

namespace Coresender\Api;

use Psr\Http\Client\ClientInterface;
use Coresender\Http\RequestBuilder;
use Coresender\Responses\ResponseBuilder;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Coresender\Exception\ValidationException;
use Coresender\Exception\AuthorizationException;
use Coresender\Exception\ApiException;

class BaseApi
{
    protected $httpClient;
    protected $requestBuilder;
    protected $responseBuilder;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->requestBuilder = new RequestBuilder();
        $this->responseBuilder = new ResponseBuilder();
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $response = $this->httpClient->sendRequest($request);

        if ($response->getStatusCode() >= 400) {
            $this->handleError($response);
        }

        return $response;
    }

    private function handleError(ResponseInterface $response): void
    {
        $statusCode = $response->getStatusCode();

        $content = json_decode($response->getBody()->getContents(), true);

        switch ($statusCode) {
            case 422:
                throw new ValidationException($content['data']['code'], $content['data']['errors'], $content['data']['code'], $statusCode);
                break;

            case 401:
                throw new AuthorizationException($content['data']['code'], $statusCode);

            default:
                throw new ApiException($content['data']['code']);

        }

    }

}