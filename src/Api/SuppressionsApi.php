<?php

declare(strict_types=1);

namespace Coresender\Api;

use Coresender\Responses\SuppressionsApi\ListResponse;

class SuppressionsApi extends BaseApi
{
    public function getList()
    {
        return $this->sendRequest('GET', '/v1/suppressions', [], ListResponse::class, self::AUTH_TYPE_OAUTH2);
    }

}
