<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Exception\GeneralApiException;
use Trunkrs\SDK\Exception\NotAuthorizedException;

class GetTest extends SDKTestCase {
    public function testEmitsGetMethodRequest() {
        $this->mockResponseCallback(function($method) {
            $this->assertEquals("GET", $method);

            return ["status" => 200];
        });

        RequestHandler::get("shipments");
    }

    public function testPassesQueryStringAsParams() {
        $body = [
            "foo" => uniqid(),
            "bar" => uniqid(),
        ];
        $this->mockResponseCallback(function($method, $url, $headers, $params) use ($body) {
            $this->assertEquals($body, $params);

            return ["status" => 200];
        });

        RequestHandler::get("shipments", $body);
    }

    public function testExtractsJsonBodyFromSuccessfulRequest() {
        $this->mockResponse(200, ["success" => true]);

        $result = RequestHandler::get("shipment");

        self::assertTrue($result->success);
    }

    public function testDoesNotAddUnecessaryContentTypeHeader() {
        $this->mockResponseCallback(function($method, $url, $headers) {
            $this->assertArrayNotHasKey('Content-Type', $headers);

            return ["status" => 200];
        });

        RequestHandler::get("shipments");
    }

    public function testThrowsWhenNotAuthorized() {
        $this->mockResponse(401);
        $this-> expectException(NotAuthorizedException::class);

        RequestHandler::get("shipments");
    }

    public function testThrowsWhenServerError() {
        $this->mockResponse(500, ["message" => "Whoops!"]);
        $this->expectException(GeneralApiException::class);

        RequestHandler::get("shipments");
    }
}