<?php

declare(strict_types=1);

namespace Coresender\Api;

use Coresender\Http\RequestBuilder;
use Coresender\Responses\ResponseBuilder;
use Psr\Http\Message\ResponseInterface;
use Coresender\Exception\ValidationException;
use Coresender\Exception\AuthorizationException;
use Coresender\Exception\ApiException;
use Coresender\Http\ClientFactory;
use Coresender\Responses\PublicApi\LoginApiResponse;

class BaseApi
{
    protected const AUTH_TYPE_NONE = 'none';
    protected const AUTH_TYPE_BASIC = 'basic';
    protected const AUTH_TYPE_OAUTH2 = 'oauth2';

    private static $httpClient;
    private static $accessToken;

    protected $options;
    protected $requestBuilder;
    protected $responseBuilder;

    public function __construct(array $options)
    {
        $this->options = $options;
        $this->requestBuilder = new RequestBuilder();
        $this->responseBuilder = new ResponseBuilder();
    }

    protected function post(string $uri, array $data, string $responseClass, $authType = self::AUTH_TYPE_NONE)
    {
        return $this->sendRequest('POST', $uri, $data, $responseClass, $authType);
    }

    protected function sendRequest(string $method, string $uri, array $data, string $responseClass, $authType = self::AUTH_TYPE_NONE)
    {
        if (!self::$httpClient) {
            self::$httpClient = $options['httpClient'] ?? ClientFactory::createClient($this->options['endpoint']);
        }

        $request = $this->requestBuilder->buildRequest($method, $uri, $data);

        if ($authType === self::AUTH_TYPE_BASIC) {
            $request = $request->withHeader('Authorization', 'Basic '.base64_encode(sprintf('%s:%s', $this->options['sendingAccountId'], $this->options['sendingAccountKey'])));
        }

        if ($authType === self::AUTH_TYPE_OAUTH2) {
            $this->login();
            $request = $request->withHeader('Authorization', 'Bearer '.self::$accessToken);
        }

        $request = $request->withHeader('Accept', 'application/json');

        $response = self::$httpClient->sendRequest($request);

        if ($response->getStatusCode() >= 400) {
            $this->handleError($response);
        }

        return $this->responseBuilder->build($response, $responseClass);
    }

    private function login(): void
    {
        if (self::$accessToken) {
            return;
        }

        $data = ['grant_type' => 'password', 'email' => $this->options['username'], 'password' => $this->options['password']];

        /** @var LoginApiResponse $loginResponse */
        $loginResponse = $this->sendRequest('POST', '/v1/login', $data, LoginApiResponse::class);

        self::$accessToken = $loginResponse->getAccessToken();
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