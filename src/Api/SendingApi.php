<?php

declare(strict_types=1);

namespace Coresender\Api;

use Coresender\Responses\SendingApi\SendEmailApiResponse;
use Coresender\Responses\SendingApi\SendEmailResponseItem;

class SendingApi extends BaseApi
{
    private $batch = [];

    public function addToBatch(array $email): void
    {
        $this->batch[] = $email;
    }

    public function execute(): SendEmailApiResponse
    {
        $response = $this->send($this->batch);

        $this->batch = [];

        return $response;
    }

    public function simpleEmail(array $email): SendEmailResponseItem
    {
        $response = $this->send([$email]);

        return $response->getItems()[0];
    }

    private function send(array $data): SendEmailApiResponse
    {
        return $this->post('/v1/send_email', $data, SendEmailApiResponse::class, self::AUTH_TYPE_BASIC);
    }
}
