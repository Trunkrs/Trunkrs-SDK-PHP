<?php

namespace Trunkrs\SDK;

class ShipmentV2RetrieveTest extends APIV2TestCase
{
    public function testShouldExecuteGetRequest() {
        $this->mockResponseCallback(function ($method) {
            $this->assertEquals("GET", $method);

            return [
                "status" => 200,
                "body" => json_encode([MockV2Responses::getFakeShipmentBody()])
            ];
        });

        Shipment::retrieve();
    }

    public function testShouldRetrieveShipments() {
        $response = ['data' => [MockV2Responses::getFakeShipmentBody()]];
        $this->mockResponse(200, $response);

        $result = Shipment::retrieve();

        $this->assertCount(1, $result);
        $this->assertInstanceOf(Shipment::class, $result[0]);
    }

    public function testShouldSupplyPageParameters() {
        $page = 3;
        $this->mockResponseCallback(function ($method, $url, $headers, $params) use ($page) {
            $this->assertArraySubset([
                'offset' => 100,
                'limit' => 50,
            ], $params);

            return [
                "status" => 200,
                "body" => json_encode([MockV2Responses::getFakeShipmentBody()])
            ];
        });

        Shipment::retrieve($page);
    }
}