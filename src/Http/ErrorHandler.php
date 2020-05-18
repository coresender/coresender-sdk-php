<?php

declare(strict_types=1);

namespace Coresender\Http;

use Psr\Http\Message\ResponseInterface;
use Coresender\Exception\ValidationException;
use Coresender\Exception\AuthorizationException;
use Coresender\Exception\ApiException;

class ErrorHandler
{
    public function handle(ResponseInterface $response): void
    {
        $statusCode = $response->getStatusCode();

        $content = json_decode((string) $response->getBody(), true);

        switch ($statusCode) {
            case 422:
                throw new ValidationException($content['data']['code'], $content['data']['errors'], $this->validationErrorsToMessage($content['data']['errors']), $statusCode);
                break;

            case 401:
                throw new AuthorizationException($content['data']['code'], $statusCode);

            default:
                throw new ApiException($content['data']['code'], $content['data']['errors'], $this->errorsToMessage($content['data']['errors']));
        }
    }

    private function validationErrorsToMessage(array $errors): string
    {
        $messages = [];

        foreach ($errors AS $error) {
            foreach ($error['errors'] AS $fieldError) {
                $messages[] = $error['field'] . ': '.$fieldError['description'];
            }
        }

        return join('; ', $messages);
    }

    private function errorsToMessage(array $errors): string
    {
        return join('; ', array_map(function ($error) {
            return $error['description'];
        }, $errors));
    }

}
