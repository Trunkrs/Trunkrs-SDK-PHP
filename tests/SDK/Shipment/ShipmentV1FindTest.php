<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Exception\ShipmentNotFoundException;

class ShipmentV1FindTest extends APIV1TestCase {
    public function testShouldExecuteAGetRequest() {
        $this->mockResponseCallback(function ($method) {
            $this->assertEquals("GET", $method);

            return [
                "status" => 200,
                "body" => json_encode(Mocks::getFakeShipmentBody())
            ];
        });

        Shipment::find(100);
    }

    public function testShouldFindShipmentById() {
        $shipmentId = Mocks::getGenerator()->randomNumber();
        $this->mockResponse(200, Mocks::getFakeShipmentBody($shipmentId));

        $shipment = Shipment::find($shipmentId);

        $this->assertInstanceOf(Shipment::class, $shipment);
        $this->assertAttributeEquals($shipmentId, 'id', $shipment);
    }

    public function testShouldThrowWhenNotFound() {
        $this->expectException(ShipmentNotFoundException::class);
        $this->mockResponse(404);

        Shipment::find(100);
    }
}