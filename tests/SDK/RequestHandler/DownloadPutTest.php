<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Exception\GeneralApiException;
use Trunkrs\SDK\Exception\NotAuthorizedException;
use Trunkrs\SDK\Exception\ServerValidationException;

class DownloadPutTest extends SDKTestCase
{
    private $_mockBody = ["foo" => "bar"];

    public function testEmitsPutMethodRequest() {
        $this->mockDownloadCallback(function($method) {
            $this->assertEquals("PUT", $method);

            return ["status" => 200];
        });

        RequestHandler::downloadPut("shipments/labels", "/tmp/foo.bar", $this->_mockBody);
    }

    public function testPassesBody() {
        $body = [
            "foo" => uniqid(),
            "bar" => uniqid(),
        ];
        $this->mockDownloadCallback(function($method, $url, $filename, $headers, $params) use ($body) {
            $this->assertEquals($body, $params);

            return ["status" => 200];
        });

        RequestHandler::downloadPut("shipments", "/tmp/foo.bar", $body);
    }

    public function testHasJsonContentType() {
        $this->mockDownloadCallback(function($method, $url, $filename, $headers) {
            $this->assertArraySubset([
                'Content-Type' => 'application/json; charset=utf-8'
            ], $headers);

            return ["status" => 200];
        });

        RequestHandler::downloadPut("shipments/labels", "/tmp/foo.bar", $this->_mockBody);
    }

    public function testThrowsWhenNotAuthorized() {
        $this->mockDownload(401);
        $this-> expectException(NotAuthorizedException::class);

        RequestHandler::downloadPut("shipments", "/tmp/foo.bar", $this->_mockBody);
    }

    public function testThrowsWhenValidationError() {
        $this->mockDownload(422);
        $this-> expectException(ServerValidationException::class);

        RequestHandler::downloadPut("shipments", "/tmp/foo.bar", $this->_mockBody);
    }

    public function testThrowsWhenServerError() {
        $this->mockDownload(500, null, ["message" => "Whoops!"]);
        $this->expectException(GeneralApiException::class);

        RequestHandler::downloadPut("shipments", "/tmp/foo.bar", $this->_mockBody);
    }
}