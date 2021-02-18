<?php

namespace Trunkrs\SDK;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use PHPUnit\Framework\TestCase;
use Trunkrs\SDK\HTTP\HttpClientInterface;

abstract class SDKTestCase extends TestCase {
    protected $mockClient;

    private $apiKey;
    private $clientId;
    private $clientSecret;
    private $version;

    protected function setUp()
    {
        parent::setUp();

        $this->apiKey = Settings::$apiKey;
        $this->clientId = Settings::$clientId;
        $this->clientSecret = Settings::$clientSecret;
        $this->version = Settings::$apiVersion;

        $this->mockClient = $this->createMock(HttpClientInterface::class);
        RequestHandler::setHttpClient($this->mockClient);
    }

    protected function mockResponse(int $status, $body = null, array $headers = []) {
        $this->mockClient->method("request")->will($this->returnValue([
            "status" => $status,
            "body" => json_encode($body),
            "headers" => $headers,
        ]));
    }

    protected function mockResponseCallback(callable $callback) {
        $this->mockClient->method("request")->will($this->returnCallback($callback));
    }

    protected function mockDownload(int $status, string $content = null, $body = null, array $headers = []) {
        $this->mockClient->method("download")->will($this->returnCallback(
            function ($method, $url, $filename) use ($content, $headers, $body, $status) {
                if (!is_null($content)) {
                    $fileHandle = fopen($filename, 'w');
                    fwrite($fileHandle, $content);
                    fclose($fileHandle);
                }

                return [
                    "status" => $status,
                    "body" => $body,
                    "headers" => $headers,
                ];
            }
        ));
    }

    protected function mockDownloadCallback(callable $callback) {
        $this->mockClient->method("download")->will($this->returnCallback($callback));
    }

    protected function mockGuzzleClient(array $responses): Client {
        return new Client([
            'handler' => new MockHandler($responses),
        ]);
    }

    public function tearDown()
    {
        Settings::$apiKey = $this->apiKey;
        Settings::$clientId = $this->clientId;
        Settings::$clientSecret = $this->clientSecret;
        Settings::$apiVersion = $this->version;

        parent::tearDown();
    }
}