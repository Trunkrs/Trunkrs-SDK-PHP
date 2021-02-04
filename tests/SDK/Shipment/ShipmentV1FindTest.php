<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Exception\NotSupportedException;
use Trunkrs\SDK\Exception\ShipmentNotFoundException;

class ShipmentV1FindTest extends APIV1TestCase {
    public function testShouldExecuteAGetRequest() {
        $this->mockResponseCallback(function ($method) {
            $this->assertEquals("GET", $method);

            return [
                "status" => 200,
                "body" => json_encode(MockV1Responses::getFakeShipmentBody())
            ];
        });

        Shipment::findById(100);
    }

    public function testShouldFindShipmentById() {
        $shipmentId = Mocks::getGenerator()->randomNumber();
        $this->mockResponse(200, MockV1Responses::getFakeShipmentBody($shipmentId));

        $shipment = Shipment::findById($shipmentId);

        $this->assertInstanceOf(Shipment::class, $shipment);
        $this->assertAttributeEquals($shipmentId, 'id', $shipment);
    }

    public function testShouldThrowWhenNotFound() {
        $this->expectException(ShipmentNotFoundException::class);
        $this->mockResponse(404);

        Shipment::findById(100);
    }

    public function testShouldThrowSupportException() {
        $this->expectException(NotSupportedException::class);

        Shipment::find(Mocks::getTrunkrsNr());
    }
}