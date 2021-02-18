<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Exception\NotSupportedException;
use Trunkrs\SDK\Exception\ShipmentNotFoundException;

class ShipmentV2FindTest extends APIV2TestCase
{
    public function testShouldExecuteGETRequest() {
        $this->mockResponseCallback(function ($method) {
            $this->assertEquals("GET", $method);

            return [
                "status" => 200,
                "body" => json_encode(MockV2Responses::getFakeShipmentBody())
            ];
        });

        Shipment::find(Mocks::getTrunkrsNr());
    }

    public function testShouldFindShipmentByTrunkrsNr() {
        $this->mockResponse(200, MockV2Responses::getFakeShipmentBody());

        $shipment = Shipment::find(Mocks::getTrunkrsNr());

        $this->assertInstanceOf(Shipment::class, $shipment);
    }

    public function testShouldThrowWhenNotFound() {
        $this->expectException(ShipmentNotFoundException::class);
        $this->mockResponse(404);

        Shipment::find(Mocks::getTrunkrsNr());
    }

    public function testShouldThrowSupportException() {
        $this->expectException(NotSupportedException::class);

        Shipment::findById(Mocks::getGenerator()->randomNumber());
    }
}