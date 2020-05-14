<?php

namespace Coresender\Tests\Responses;

use PHPUnit\Framework\TestCase;
use Coresender\CoresenderSendingApi;
use Coresender\Helpers\EmailBuilder;
use Coresender\Exception\ValidationException;

class SendingApiTest extends TestCase
{

    /** @test */
    public function it_should_send_email(): void
    {
        $accountId = '22e9a458-9bba-4cda-9ebe-97b4755d1b60';
        $key = 'a3b4f3e3-48d5-409d-8f0a-52b95284418a';

        $sendingApi = CoresenderSendingApi::create($accountId, $key);

        $builder = new EmailBuilder();
        $builder
            ->setFrom('p.pankowski@gmail.com')
            ->addToRecipient('p.pankowski@gmail.com')
            ->setSubject('Ala ma kota')
            ->setBodyText('A kot ma Alę')
        ;

        try {
            $response = $sendingApi->sendEmail([ $builder->getData() ]);

            dump($response);
        }
        catch (ValidationException $e) {
            dump($e->getErrorCode(), $e->getErrors());
        }

    }
}
