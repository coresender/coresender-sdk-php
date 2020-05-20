<?php

declare(strict_types=1);

namespace Coresender\Responses\PublicApi;

use Coresender\Responses\ApiResponseInterface;

class LoginApiResponse implements ApiResponseInterface
{
    /** @var string */
    private $tokenType;

    /** @var string */
    private $accessToken;

    /** @var string */
    private $refreshToken;

    /** @var int */
    private $expiresIn;

    public static function create(array $data): self
    {
        $response = new self();
        $response->tokenType = $data['token_type'];
        $response->accessToken = $data['access_token'];
        $response->refreshToken = $data['refresh_token'];
        $response->expiresIn = $data['expires_in'];

        return $response;
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }
}
