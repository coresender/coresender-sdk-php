<?php

declare(strict_types=1);

namespace Coresender\Api;

use Coresender\Responses\PublicApi\LoginApiResponse;

class PublicApi extends BaseApi
{
    public function login(string $username, string $password): LoginApiResponse
    {
        $data = ['grant_type' => 'password', 'email' => $username, 'password' => $password];

        return $this->post('/v1/login', $data, LoginApiResponse::class);
    }
}
