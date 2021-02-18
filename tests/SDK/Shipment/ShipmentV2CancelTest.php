<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Exception\NotSupportedException;
use Trunkrs\SDK\Exception\ShipmentNotFoundException;

class ShipmentV2CancelTest extends APIV2TestCase
{
    public function testShouldExecuteDeleteRequest() {
        $this->mockResponseCallback(function ($method) {
            $this->assertEquals("DELETE", $method);
            return ["status" => 204];
        });

        Shipment::cancelByTrunkrsNr(Mocks::getTrunkrsNr());
    }

    public function testShouldExecuteDeleteFromInstanceCancel() {
        $shipment = new Shipment(MockV2Responses::getFakeShipmentBody());
        $this->mockResponseCallback(function ($method, $url) use ($shipment) {
            $this->assertEquals("DELETE", $method);
            $this->assertContains(sprintf("/%d", $shipment->trunkrsNr), $url);

            return ["status" => 204];
        });

        $shipment->cancel();
    }

    public function testShouldThrowWhenShipmentNotFound() {
        $this->mockResponse(404);
        $this->expectException(ShipmentNotFoundException::class);

        Shipment::cancelByTrunkrsNr(Mocks::getTrunkrsNr());
    }

    public function testShouldThrowSupportException() {
        $this->expectException(NotSupportedException::class);

        Shipment::cancelById(Mocks::getGenerator()->randomNumber());
    }
}