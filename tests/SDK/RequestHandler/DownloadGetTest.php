<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Exception\GeneralApiException;
use Trunkrs\SDK\Exception\NotAuthorizedException;

class DownloadGetTest extends SDKTestCase
{
    public function testEmitsGetMethodRequest() {
        $this->mockDownloadCallback(function($method) {
            $this->assertEquals("GET", $method);

            return ["status" => 200];
        });

        RequestHandler::downloadGet("shipments/labels", "/tmp/foo.bar");
    }

    public function testThrowsWhenNotAuthorized() {
        $this->mockDownload(401);
        $this-> expectException(NotAuthorizedException::class);

        RequestHandler::downloadGet("shipments/labels", "/tmp/foo.bar");
    }

    public function testThrowsWhenServerError() {
        $this->mockDownload(500, null, ["message" => "Whoops!"]);
        $this->expectException(GeneralApiException::class);

        RequestHandler::downloadGet("shipments/labels", "/tmp/foo.bar");
    }
}