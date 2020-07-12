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
}