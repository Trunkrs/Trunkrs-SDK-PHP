<?php

namespace Trunkrs\SDK;

class ShipmentV1RetrieveTest extends APIV1TestCase {
    public function testShouldExecuteGetRequest() {
        $this->mockResponseCallback(function ($method) {
            $this->assertEquals("GET", $method);

            return [
                "status" => 200,
                "body" => json_encode([MockV1Responses::getFakeShipmentBody()])
            ];
        });

        Shipment::retrieve();
    }

    public function testShouldRetrieveShipments() {
        $this->mockResponse(200, [MockV1Responses::getFakeShipmentBody()]);

        $result = Shipment::retrieve();

        $this->assertCount(1, $result);
        $this->assertInstanceOf(Shipment::class, $result[0]);
    }

    public function testShouldSupplyPageParameter() {
        $page = Mocks::getGenerator()->randomDigit;
        $this->mockResponseCallback(function ($method, $url, $headers, $params) use ($page) {
            $this->assertArraySubset([
                'page' => $page,
            ], $params);

            return [
                "status" => 200,
                "body" => json_encode([MockV1Responses::getFakeShipmentBody()])
            ];
        });

        Shipment::retrieve($page);
    }
}