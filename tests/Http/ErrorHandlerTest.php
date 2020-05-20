<?php

namespace Coresender\Tests\Http;

use PHPUnit\Framework\TestCase;
use Nyholm\Psr7\Response;
use Coresender\Http\ErrorHandler;
use Coresender\Exception\ValidationException;
use Coresender\Exception\AuthorizationException;
use Coresender\Exception\InvalidJsonException;
use Coresender\Exception\ApiException;
use Exception;

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
        $statusCode = 422;
        $body = <<<'EOF'
{"data":{"code":"VALIDATION_ERROR","errors":[{"field":"suppression_id","value":null,"errors":[{"code":"UUID","description":"The 'suppression_id' must be a valid UUID."}]}]},"meta":{"rq_time":"0.0003s"}}
EOF;

        $response = new Response($statusCode, [], $body);

        try {
            $this->errorHandler->handle($response);
        } catch (Exception $exception) {
            /** @var ValidationException $exception */
            $this->assertInstanceOf(ValidationException::class, $exception);
            $this->assertEquals("field suppression_id: UUID: The 'suppression_id' must be a valid UUID.", $exception->getMessage());
            $this->assertCount(1, $exception->getErrors());
        }
    }

    /** @test */
    public function it_should_handle_invalid_json_error(): void
    {
        $this->expectException(InvalidJsonException::class);

        $statusCode = 400;
        $body = <<<'EOF'
{"data":{"code":"INVALID_JSON","errors":[{"code":"INVALID_JSON","description":"Your request body contains an invalid JSON structure. Please check your syntax."}]},"meta":{"rq_time":"2.719251ms"}}
EOF;

        $response = new Response($statusCode, [], $body);

        $this->errorHandler->handle($response);
    }

    /** @test */
    public function it_should_handle_invalid_credentials(): void
    {
        $this->expectException(AuthorizationException::class);

        $statusCode = 401;
        $body = <<<'EOF'
{"data":{"code":"INVALID_CREDENTIALS","errors":[{"code":"INVALID_CREDENTIALS","description":"The username (account_id) and password (api_key) combination you have supplied is not valid. Please check it and try again."}]},"meta":{"rq_time":"1.892817ms"}}
EOF;

        $response = new Response($statusCode, [], $body);

        $this->errorHandler->handle($response);
    }

    /** @test */
    public function it_should_handle_internal_server_error(): void
    {
        $this->expectException(ApiException::class);

        $statusCode = 500;
        $response = new Response($statusCode);

        $this->errorHandler->handle($response);
    }

    /** @test */
    public function it_should_handle_not_found_error(): void
    {
        $this->expectException(ApiException::class);

        $statusCode = 409;
        $body = <<<'EOF'
{"data":{"code":"SUPPRESSION_NOT_FOUND","errors":[{"code":"SUPPRESSION_NOT_FOUND","description":"The suppression_id you've submitted was not found"}]},"meta":{"rq_time":"0.0003s"}}
EOF;

        $response = new Response($statusCode, [], $body);

        $this->errorHandler->handle($response);
    }
}
