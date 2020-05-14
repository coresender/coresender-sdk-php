<?php

namespace Coresender\Tests\Responses;

use PHPUnit\Framework\TestCase;
use Coresender\Responses\ResponseBuilder;
use GuzzleHttp\Psr7\Response;
use Coresender\Responses\ResponseInterface;

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

        $responseClass = $this->responseBuilder->build($response, TestResponse::class);

        $this->assertInstanceOf(TestResponse::class, $responseClass);
    }

}

class TestResponse implements ResponseInterface
{
    public static function create(array $data, array $meta): ResponseInterface
    {
        return new self();
    }

}

