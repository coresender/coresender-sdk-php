<?php

declare(strict_types=1);

namespace Coresender\Api;

use Coresender\Http\RequestBuilder;
use Coresender\Responses\ResponseBuilder;
use Coresender\Http\ClientFactory;
use Coresender\Responses\PublicApi\LoginApiResponse;
use Coresender\Http\ErrorHandler;
use Coresender\Coresender;

class BaseApi
{
    protected const AUTH_TYPE_NONE = 'none';
    protected const AUTH_TYPE_BASIC = 'basic';
    protected const AUTH_TYPE_OAUTH2 = 'oauth2';

    private static $httpClient;
    private static $accessToken;

    protected $options;
    private $requestBuilder;
    private $responseBuilder;
    private $errorHandler;

    public function __construct(array $options)
    {
        $this->options = $options;
        $this->requestBuilder = new RequestBuilder();
        $this->responseBuilder = new ResponseBuilder();
        $this->errorHandler = new ErrorHandler();
    }

    protected function get(string $uri, string $responseClass, $authType = self::AUTH_TYPE_NONE)
    {
        return $this->sendRequest('GET', $uri, [], $responseClass, $authType);
    }

    protected function post(string $uri, array $data, string $responseClass, $authType = self::AUTH_TYPE_NONE)
    {
        return $this->sendRequest('POST', $uri, $data, $responseClass, $authType);
    }

    private function sendRequest(string $method, string $uri, array $data, string $responseClass, $authType = self::AUTH_TYPE_NONE)
    {
        if (!self::$httpClient) {
            self::$httpClient = $options['httpClient'] ?? ClientFactory::createClient($this->options['endpoint']);
        }

        $request = $this->requestBuilder->buildRequest($method, $uri, $data);

        if ($authType === self::AUTH_TYPE_BASIC) {
            $request = $request->withHeader('Authorization', 'Basic ' . base64_encode(sprintf('%s:%s', $this->options['sendingAccountId'], $this->options['sendingAccountKey'])));
        }

        if ($authType === self::AUTH_TYPE_OAUTH2) {
            $this->login();
            $request = $request->withHeader('Authorization', 'Bearer ' . self::$accessToken);
        }

        $request = $request->withHeader('Accept', 'application/json');
        if ($this->options['debug']) {
            $debugUri = $request->getUri()->getPath() .
                ($request->getUri()->getQuery() ? '?' . $request->getUri()->getQuery() : '');
            Coresender::getLogger()->debug(
                "Sending {$request->getMethod()} data to {$debugUri}",
                [
                    'headers' => $request->getHeaders(),
                    'body' => $request->getBody()->getContents(),
                ]
            );
        }

        $response = self::$httpClient->sendRequest($request);
        if ($this->options['debug']) {
            Coresender::getLogger()->debug(
                "Received response: {$response->getStatusCode()} {$response->getReasonPhrase()}",
                [
                    'headers' => $response->getHeaders(),
                    'body' => $response->getBody()->getContents(),
                ]
            );
        }

        if ($response->getStatusCode() >= 400) {
            Coresender::getLogger()->error(sprintf('Got %s response from %s %s request', $response->getStatusCode(), $method, $uri));
            $this->errorHandler->handle($response);
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
        $loginResponse = $this->post('/v1/login', $data, LoginApiResponse::class);

        self::$accessToken = $loginResponse->getAccessToken();
    }
}
