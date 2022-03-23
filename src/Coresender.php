<?php

declare(strict_types=1);

namespace Coresender;

use Coresender\Api\SendEmail;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Coresender
{
    private $options = []; // FIXME Use DTO

    /** @var string */
    private $endpoint;

    /** @var LoggerInterface */
    private static $logger;

    public function __construct(
        ?string $username = null,
        ?string $password = null,
        ?string $sendingAccountId = null,
        ?string $sendingAccountKey = null
    ) {
        $envs = getenv();

        $this->options['debug'] = $envs['CORESENDER_DEBUG'] ?? false;
        $this->options['username'] = $username ?: ($envs['CORESENDER_USERNAME'] ?? null);
        $this->options['password'] = $password ?: ($envs['CORESENDER_PASSWORD'] ?? null);
        $this->options['sendingAccountId'] = $sendingAccountId ?? ($envs['CORESENDER_SENDING_API_ID'] ?? null);
        $this->options['sendingAccountKey'] = $sendingAccountKey ?? ($envs['CORESENDER_SENDING_API_KEY'] ?? null);
        $this->options['endpoint'] = $this->endpoint = getenv('CORESENDER_API_ENDPOINT') ?: 'https://api.coresender.com';
    }

    public static function createSendEmailApi(?string $sendingAccountId = null, ?string $sendingAccountKey = null): SendEmail
    {
        return (new self(null, null, $sendingAccountId, $sendingAccountKey))->sendEmail();
    }

    public function sendEmail(): SendEmail
    {
        return new SendEmail($this->options);
    }

    public static function setLogger(LoggerInterface $logger): void
    {
        self::$logger = $logger;
    }

    public static function getLogger(): LoggerInterface
    {
        if (!self::$logger) {
            self::$logger = new NullLogger();
        }

        return self::$logger;
    }
}
