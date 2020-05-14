<?php

declare(strict_types=1);

namespace Coresender\Responses;

use Psr\Http\Message\ResponseInterface;
use Coresender\Exception\JsonDecodeException;
use Coresender\Exception\MalformedResponseException;

class ResponseBuilder
{
    public function build(ResponseInterface $response, string $responseClass)
    {
        $content = $response->getBody()->getContents();

        $data = $this->jsonDecode($content);

        $this->validateResponse($data);

        return call_user_func([$responseClass, 'create'], $data['data'], $data['meta']);
    }

    private function jsonDecode(string $content): array
    {
        $data = json_decode($content, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new JsonDecodeException('json_decode error: ' . json_last_error_msg());
        }

        return $data;
    }

    private function validateResponse(array $data): void
    {
        if (!isset($data['data'])) {
            throw new MalformedResponseException('data key is missing');
        }

        if (!isset($data['meta'])) {
            throw new MalformedResponseException('meta key is missing');
        }
    }
}
