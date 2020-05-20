<?php

declare(strict_types=1);

namespace Coresender\Api;

use Coresender\Responses\SendingApi\SendEmailApiResponse;

class SendingApi extends BaseApi
{
    private $emails = [];

    public function scheduleEmail(array $email): void
    {
        $this->emails[] = $email;
    }

    public function execute(): SendEmailApiResponse
    {
        $response = $this->send($this->emails);

        $this->emails = [];

        return $response;
    }

    public function simpleEmail(array $email): SendEmailApiResponse
    {
        return  $this->send([$email]);
    }

    private function send(array $data): SendEmailApiResponse
    {
        return $this->post('/v1/send_email', $data, SendEmailApiResponse::class, self::AUTH_TYPE_BASIC);
    }
}
