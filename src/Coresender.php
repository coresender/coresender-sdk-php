<?php

declare(strict_types=1);

namespace Coresender;

use Coresender\Api\SendingApi;
use Coresender\Api\SuppressionsApi;

class Coresender
{
    private $options = []; // FIXME Use DTO

    /** @var string */
    private $endpoint = 'https://api.coresender.com';

    public function __construct(
        ?string $username = null,
        ?string $password = null,
        ?string $sendingAccountId = null,
        ?string $sendingAccountKey = null
    ) {
        $envs = getenv();

        $this->options['username'] = $username ?: ($envs['CORESENDER_USERNAME'] ?? null);
        $this->options['password'] = $password ?: ($envs['CORESENDER_PASSWORD'] ?? null);
        $this->options['sendingAccountId'] = $sendingAccountId ?? ($envs['CORESENDER_SENDING_API_ID'] ?? null);
        $this->options['sendingAccountKey'] = $sendingAccountKey ?? ($envs['CORESENDER_SENDING_API_KEY'] ?? null);
        $this->options['endpoint'] = $this->endpoint;

    }

    public static function createSendingApi(?string $sendingAccountId = null, ?string $sendingAccountKey = null): SendingApi
    {
        return (new self(null, null, $sendingAccountId, $sendingAccountKey))->sendingApi();
    }

    public function sendingApi(): SendingApi
    {
        return new SendingApi($this->options);
    }

    public function suppressionsApi(): SuppressionsApi
    {
        return new SuppressionsApi($this->options);
    }
}
