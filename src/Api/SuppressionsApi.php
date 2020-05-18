<?php

declare(strict_types=1);

namespace Coresender\Api;

use Coresender\Responses\SuppressionsApi\ListResponse;
use Coresender\Responses\SuppressionsApi\GetResponse;

class SuppressionsApi extends BaseApi
{
    public function getList()
    {
        return $this->sendRequest('GET', '/v1/suppressions', [], ListResponse::class, self::AUTH_TYPE_OAUTH2);
    }

    public function get($id)
    {
        return $this->sendRequest('GET', "/v1/suppressions/{$id}", [], GetResponse::class, self::AUTH_TYPE_OAUTH2);
    }
}
