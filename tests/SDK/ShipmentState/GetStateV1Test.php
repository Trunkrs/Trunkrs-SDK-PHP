<?php

namespace Trunkrs\SDK;

class GetStateV1Test extends APIV1TestCase {
    public function testShouldEmitGetRequest() {
        $this->mockResponseCallback(function ($method) {
            $this->assertEquals("GET", $method);

            return [
                "status" => 200,
                "body" => json_encode(MockV1Responses::getShipmentStateBody()),
            ];
        });

        ShipmentState::forShipment(100);
    }

    public function testShouldRequestState() {
        $this->mockResponse(200, MockV1Responses::getShipmentStateBody());

        $result = ShipmentState::forShipment(100);

        $this->assertInstanceOf(ShipmentState::class, $result);
    }
}