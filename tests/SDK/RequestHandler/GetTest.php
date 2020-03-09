<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Exception\GeneralApiException;
use Trunkrs\SDK\Exception\NotAuthorizedException;

class GetTest extends SDKTestCase {
    public function testExtractsJsonBodyFromSuccessfulRequest() {
        $this->mockResponse(200, ["success" => true]);

        $result = RequestHandler::get("shipment");

        self::assertTrue($result->success);
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