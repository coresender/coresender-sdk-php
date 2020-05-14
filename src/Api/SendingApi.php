<?php

declare(strict_types=1);

namespace Coresender\Api;

use Coresender\Responses\SendingApi\SendEmailResponse;

class SendingApi extends BaseApi
{

    public function sendEmail(array $data): SendEmailResponse
    {
        $request = $this->requestBuilder->buildRequest('POST', '/v1/send_email', $data);

        $response = $this->sendRequest($request);

        return $this->responseBuilder->build($response, SendEmailResponse::class);
    }
}
