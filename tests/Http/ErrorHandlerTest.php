<?php

namespace Coresender\Tests\Http;

use PHPUnit\Framework\TestCase;
use Nyholm\Psr7\Response;
use Coresender\Http\ErrorHandler;
use Coresender\Exception\ValidationException;

class ErrorHandlerTest extends TestCase
{
    /** @var ErrorHandler */
    private $errorHandler;

    public function setUp(): void
    {
        parent::setUp();

        $this->errorHandler = new ErrorHandler();
    }

    /** @test */
    public function it_should_handle_validation_error(): void
    {
        $this->expectException(ValidationException::class);

        $body = <<<EOF
{"data":{"code":"VALIDATION_ERROR","errors":[{"field":"suppression_id","value":null,"errors":[{"code":"UUID","description":"The 'suppression_id' must be a valid UUID."}]}]},"meta":{"rq_time":"0.0003s"}}
EOF;

        $response = new Response(422, [], $body);

        $this->errorHandler->handle($response);
    }

}
