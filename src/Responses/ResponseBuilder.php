<?php

declare(strict_types=1);

namespace Coresender\Responses;

use Psr\Http\Message\ResponseInterface;
use Coresender\Exception\JsonDecodeException;

class ResponseBuilder
{
    public function build(ResponseInterface $response, string $responseClass)
    {
        $content = (string) $response->getBody();

        $data = $this->jsonDecode($content);

        return call_user_func([$responseClass, 'create'], $data);
    }

    private function jsonDecode(string $content): array
    {
        $data = json_decode($content, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new JsonDecodeException('json_decode error: ' . json_last_error_msg());
        }

        return $data;
    }
}
