<?php

declare(strict_types=1);

namespace Coresender;

use Coresender\Http\ClientFactory;
use Coresender\Api\SendingApi;
use Coresender\Api\SuppressionApi;

class Coresender
{
    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var string */
    private $sendingAccountId;

    /** @var string */
    private $sendingAccountKey;

    /** @var string */
    private $endpoint = 'https://api.coresender.com';


    public function __construct(
        ?string $username = null,
        ?string $password = null,
        ?string $sendingAccountId = null,
        ?string $sendingAccountKey = null
    ) {
        $envs = getenv();

        $this->username = $username ?: ($envs['CORESENDER_USERNAME'] ?? null);
        $this->password = $password ?: ($envs['CORESENDER_PASSWORD'] ?? null);
        $this->sendingAccountId = $sendingAccountId ?? ($envs['CORESENDER_SENDING_API_ID'] ?: null);
        $this->sendingAccountKey = $sendingAccountKey ?? ($envs['CORESENDER_SENDING_API_KEY'] ?: null);
    }

    public static function createSendingApi(?string $sendingAccountId = null, ?string $sendingAccountKey = null): SendingApi
    {
        return (new self(null, null, $sendingAccountId, $sendingAccountKey))->sendingApi();
    }

    public function sendingApi(): SendingApi
    {
        $httpClient = ClientFactory::createBasicAuthClient($this->sendingAccountId, $this->sendingAccountKey, $this->endpoint);

        return new SendingApi($httpClient);
    }

    public function suppression(): SuppressionApi
    {

    }
}
