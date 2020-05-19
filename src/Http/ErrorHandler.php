<?php

declare(strict_types=1);

namespace Coresender\Http;

use Psr\Http\Message\ResponseInterface;
use Coresender\Exception\ValidationException;
use Coresender\Exception\AuthorizationException;
use Coresender\Exception\ApiException;
use Coresender\Exception\InvalidJsonException;

class ErrorHandler
{
    public function handle(ResponseInterface $response): void
    {
        $statusCode = $response->getStatusCode();

        $content = json_decode((string) $response->getBody(), true);

        $errorCode = $content && isset($content['data'], $content['data']['code']) ? $content['data']['code'] : null;

        if ($errorCode === 'INVALID_CREDENTIALS' || $statusCode === 401) {
            throw new AuthorizationException($this->errorsToMessage($content['data']['errors']), $statusCode);
        }

        if ($errorCode === 'INVALID_JSON') {
            throw new InvalidJsonException($errorCode, $content['data']['errors'], $this->errorsToMessage($content['data']['errors']), $statusCode);
        }

        if ($errorCode === 'VALIDATION_ERROR') {
            throw new ValidationException($content['data']['code'], $content['data']['errors'], $this->validationErrorsToMessage($content['data']['errors']), $statusCode);
        }

        if (is_string($errorCode)) {
            throw new ApiException($errorCode, $content['data']['errors'], $this->errorsToMessage($content['data']['errors']), $statusCode);
        }

        throw new ApiException('API_ERROR', [], sprintf('Coresender API request failed, http status code: %s', $statusCode), $statusCode);
    }

    private function validationErrorsToMessage(array $errors): string
    {
        $messages = [];
        foreach ($errors AS $error) {
            $items = [];

            foreach ($error['errors'] AS $fieldError) {
                $items[] = $fieldError['code'] . ': '.$fieldError['description'];
            }

            $messages[] = sprintf('field %s: %s', $error['field'], join(', ', $items));
        }

        return join('. ', $messages);
    }

    private function errorsToMessage(array $errors): string
    {
        return join('; ', array_map(function ($error) {
            return $error['description'];
        }, $errors));
    }

}
