<?php

declare(strict_types=1);

namespace Coresender\Api;

use Coresender\Responses\SendingApi\SendEmailResponse;

class SendingApi extends BaseApi
{
    private $emails = [];

    public function scheduleEmail(array $email): void
    {
        $this->emails[] = $email;
    }

    public function execute(): SendEmailResponse
    {
        $response = $this->send($this->emails);

        $this->emails = [];

        return $response;
    }

    public function simpleEmail(array $email): SendEmailResponse
    {
        return  $this->send([$email]);
    }

    private function send(array $data): SendEmailResponse
    {
        $request = $this->requestBuilder->post('/v1/send_email', $data);

        $response = $this->sendRequest($request);

        return $this->responseBuilder->build($response, SendEmailResponse::class);
    }
}
