<?php

namespace Coresender\Tests\Responses;

use PHPUnit\Framework\TestCase;
use Coresender\Responses\ResponseBuilder;
use Coresender\Responses\ApiResponseInterface;
use Nyholm\Psr7\Response;

class ResponseBuilderTest extends TestCase
{
    /** @var ResponseBuilder */
    private $responseBuilder;

    public function setUp(): void
    {
        parent::setUp();

        $this->responseBuilder = new ResponseBuilder();
    }

    /** @test */
    public function it_should_create_response_class(): void
    {
        $response = new Response(200, [], json_encode(['data' => [], 'meta' => []]));

        $responseClass = $this->responseBuilder->build($response, TestApiResponse::class);

        $this->assertInstanceOf(TestApiResponse::class, $responseClass);
    }
}

class TestApiResponse implements ApiResponseInterface
{
    public static function create(array $data, int $httpStatus): ApiResponseInterface
    {
        return new self();
    }
}
