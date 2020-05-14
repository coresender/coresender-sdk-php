<?php

declare(strict_types=1);

namespace Coresender;

use Coresender\Http\ClientFactory;
use Coresender\Api\SendingApi;

class CoresenderSendingApi
{
    private static $defaultEndpoint = 'https://api.coresender.com';

    public static function create(?string $accountId = null, ?string $apiKey = null, ?string $endpoint = null): SendingApi
    {
        if (!$accountId) {
            $accountId = getenv('CORESENDER_ACCOUNT_ID');
        }

        if (!$accountId) {

        }

        if (!$apiKey) {
            $apiKey = getenv('CORESENDER_API_KEY');
        }

        if (!$apiKey) {

        }

        if (!$endpoint) {
            $endpoint = self::$defaultEndpoint;
        }


        $httpClient = ClientFactory::createBasicAuthClient($accountId, $apiKey, $endpoint);

        return new SendingApi($httpClient);
    }
}
