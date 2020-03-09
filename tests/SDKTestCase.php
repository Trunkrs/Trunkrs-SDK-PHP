<?php

namespace Trunkrs\SDK;

use PHPUnit\Framework\TestCase;
use Trunkrs\SDK\HTTP\HttpClientInterface;

abstract class SDKTestCase extends TestCase {
    protected $mockClient;

    protected function setUp()
    {
        parent::setUp();

        $this->mockClient = $this->createMock(HttpClientInterface::class);
        RequestHandler::setHttpClient($this->mockClient);
    }

    protected function mockResponse(int $status, array $body = [], array $headers = []) {
        $this->mockClient->method("request")->will($this->returnValue([
            "status" => $status,
            "body" => json_encode($body),
            "headers" => $headers,
        ]));
    }

    protected function mockResponseCallback(callable $callback) {
        $this->mockClient->method("request")->will($this->returnCallback($callback));
    }
}