<?php

namespace Trunkrs\SDK;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;

use PHPUnit\Framework\TestCase;

class GuzzleTestCase extends TestCase {
    protected $mockHandler;
    protected $handlerStack;
    protected $guzzleClient;

    protected $historyContainer = [];

    private function getRequest(int $requestNr): Request {
        $transaction = $this->historyContainer[$requestNr - 1];
        $this->assertNotNull($transaction, "Expected to find a transaction in position $requestNr. Found none instead.");

        return $transaction["request"];
    }

    protected function setUp()
    {
        parent::setUp();

        $this->mockHandler = new MockHandler();
        $this->handlerStack = HandlerStack::create($this->mockHandler);

        $history = Middleware::history($this->historyContainer);
        $this->handlerStack->push($history);

        $this->guzzleClient = new Client(['handler' => $this->handlerStack]);
    }

    protected function assertRequestMethod(string $method, int $requestNr = 1) {
        $request = $this->getRequest($requestNr);

        $this->assertEquals($method, $request->getMethod());
    }

    protected function assertRequestHeaders(array $headers, int $requestNr = 1) {
        $request = $this->getRequest($requestNr);

        $requestHeaders = $request->getHeaders();
        foreach ($headers as $headerName => $headerValue) {
            $this->assertArrayHasKey($headerName, $requestHeaders);
            $requestHeaderValue = $requestHeaders[$headerName];

            $this->assertContains($headerValue, $requestHeaderValue);
        }
    }

    protected function assertRequestUrl(string $url, int $requestNr = 1) {
        $request = $this->getRequest($requestNr);

        $this->assertEquals($url, $request->getUri());
    }
}