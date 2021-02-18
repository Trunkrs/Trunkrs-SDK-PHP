<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Exception\GeneralApiException;
use Trunkrs\SDK\Exception\NotAuthorizedException;
use Trunkrs\SDK\Exception\ServerValidationException;

class PostTest extends SDKTestCase {
    private $_mockBody = ["foo" => "bar"];

    public function testEmitsPostMethodRequest() {
        $this->mockResponseCallback(function($method) {
            $this->assertEquals("POST", $method);

            return ["status" => 200];
        });

        RequestHandler::post("shipments", $this->_mockBody);
    }

    public function testPassesBodyAsParams() {
        $body = [
            "foo" => uniqid(),
            "bar" => uniqid(),
        ];
        $this->mockResponseCallback(function($method, $url, $headers, $params) use ($body) {
            $this->assertEquals($body, $params);

            return ["status" => 200];
        });

        RequestHandler::post("shipments", $body);
    }

    public function testHasJsonContentType() {
        $this->mockResponseCallback(function($method, $url, $headers) {
            $this->assertArraySubset([
                'Content-Type' => 'application/json; charset=utf-8'
            ], $headers);

            return ["status" => 200];
        });

        RequestHandler::post("shipments", $this->_mockBody);
    }

    public function testExtractsJsonBodyFromSuccessfulRequest() {
        $this->mockResponse(200, ["success" => true]);

        $result = RequestHandler::post("shipment", $this->_mockBody);

        self::assertTrue($result->success);
    }

    public function testThrowsWhenNotAuthorized() {
        $this->mockResponse(401);
        $this-> expectException(NotAuthorizedException::class);

        RequestHandler::post("shipments", $this->_mockBody);
    }

    public function testThrowsWhenValidationError() {
        $this->mockResponse(422);
        $this-> expectException(ServerValidationException::class);

        RequestHandler::post("shipments", $this->_mockBody);
    }

    public function testThrowsWhenServerError() {
        $this->mockResponse(500, ["message" => "Whoops!"]);
        $this->expectException(GeneralApiException::class);

        RequestHandler::post("shipments", $this->_mockBody);
    }
}